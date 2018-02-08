<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Problem;
use AppBundle\Entity\Solution;
use AppBundle\Entity\Tournament;
use AppBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Service\Problem as ProblemService;

class ProblemController extends Controller
{
	/**
	 * @Route("/problems", name="problems")
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getManager();
		$problems = $em->getRepository("AppBundle:Problem")->findBy(["public" => true]);

		return $this->render('AppBundle:Problem:index.html.twig', [
			"problems" => $problems
		]);
	}

    /**
     * @param Request $request
     * @param Tournament|null $tournament
     * @param Problem $problem
     *
     * @param ProblemService $problemService
     *
     * @return Response
     * @Route("/problem/{problemId}", name="problem")
     * @Route("/tournament/{tournamentId}/problem/{problemId}", name="tournament_problem")
     * @ParamConverter("problem", class="AppBundle:Problem", options={"id" = "problemId"})
     * @ParamConverter("tournament", class="AppBundle:Tournament", options={"id" = "tournamentId"})
     */
	public function problemAction(Request $request, Tournament $tournament = null, Problem $problem, ProblemService $problemService) {
        if (!$this->isAccessible($request, $tournament, $problem, 'tournament_problem')) {
            $problem = false;
        }

        $statement = $problemService->getStatement($problem);

		return $this->render('AppBundle:Problem:problem.html.twig', [
			"problem" => $problem,
            "statement" => $statement
		]);
	}

    /**
     * @param Request $request
     * @param Tournament|null $tournament
     * @param Problem $problem
     *
     * @return Response
     * @Route("/problem/{problemId}/send", name="problem_send")
     * @Route("/tournament/{tournamentId}/problem/{problemId}/send", name="tournament_problem_send")
     * @ParamConverter("problem", class="AppBundle:Problem", options={"id" = "problemId"})
     * @ParamConverter("tournament", class="AppBundle:Tournament", options={"id" = "tournamentId"})
     */
    public function sendAction(Request $request, Tournament $tournament = null, Problem $problem) {
        if (!$this->isAccessible($request, $tournament, $problem, 'tournament_problem_send')) {
            $problem = false;
        }

        $em = $this->getDoctrine()->getManager();

        /** @var User $currentUser */
        $currentUser = $this->getUser();
        $form = $this->createFormBuilder()
                     ->add('compiler', EntityType::class, ['class' => 'AppBundle\Entity\Language', 'choice_label' => 'title'])
                     ->add("code_input", TextareaType::class, ['label' => false, 'attr' => ['style' => 'display: none']])
                     ->add("submit", SubmitType::class)
                     ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $waitingStatus = $em->getRepository("AppBundle:Status")->find(10);

            $language = $em->getRepository('AppBundle:Language')->find($form->get('compiler')->getData()->getId());

            $solution = new Solution();
            $solution->setProblem($problem);
            $solution->setLanguage($language);
            $solution->setUser($currentUser);
            $solution->setSourceCode($form->get('code_input')->getData());
            $solution->setStatus($waitingStatus);
            $solution->setTime(0);
            $em->persist($solution);
            $em->flush();

            $problemService = $this->get("app.service.problem");
            $problemService->runChecker($solution);

            return $this->redirectToRoute("solutions");
        }

        return $this->render('AppBundle:Problem:send.html.twig', [
            "problem" => $problem,
            "form" => $form->createView()
        ]);
    }

    /**
     * @param Problem $problem
     *
     * @Route("/problem/{problemId}/statistics", name="problem_statistics")
     * @ParamConverter("problem", class="AppBundle:Problem", options={"id" = "problemId"})
     *
     * @return Response
     */
    public function statisticsAction(Problem $problem) {
        if (!$problem->getPublic()) {
            throw $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();

        $solutionsCount = $em->getRepository('AppBundle:Solution')
                             ->getProblemSolutionsCount($problem);

        $acceptedSolutionsCount = $em->getRepository('AppBundle:Solution')
                                     ->getProblemAcceptedSolutionsCount($problem);

        $solutionsSentUsersCount = $em->getRepository('AppBundle:Solution')
                                      ->getProblemSolutionsSentUsersCount($problem);

        $solvedUsersCount = $em->getRepository('AppBundle:Solution')
                               ->getProblemSolvedUsersCount($problem);

        return $this->render('AppBundle:Problem:statistics.html.twig', [
            "problem" => $problem,
            "solutionsCount" => $solutionsCount,
            "acceptedSolutionsCount" => $acceptedSolutionsCount,
            "solutionsSentUsersCount" => $solutionsSentUsersCount,
            "solvedUsersCount" => $solvedUsersCount
        ]);
    }

    /**
     * @param Problem $problem
     *
     * @Route("/problem/{problemId}/discussion", name="problem_discussion")
     * @ParamConverter("problem", class="AppBundle:Problem", options={"id" = "problemId"})
     *
     * @return Response
     */
	public function discussionAction(Problem $problem) {
        if (!$problem->getPublic()) {
            throw $this->createNotFoundException();
        }

	    return $this->render('AppBundle:Problem:discussion.html.twig', [
            "problem" => $problem,
        ]);
    }

    private function isAccessible(
        Request $request,
        Tournament $tournament = null,
        Problem $problem,
        $tournamentProblemRoute
    ) {
        $user = $this->getUser();
        $problems = $tournament ? $tournament->getProblems() : null;

        if ($request->get('_route') == $tournamentProblemRoute) {
            if (!$problems->contains($problem)) {
                throw $this->createNotFoundException();
            } else {
                if ($tournament->isNotStarted() || !$tournament->hasAccess($user)) {
                    if (!$tournament->hasAccess($user)) {
                        throw $this->createAccessDeniedException('You haven\'t access');
                    } else {
                        throw $this->createAccessDeniedException('Not started');
                    }
                }
            }
        } elseif (!$problem->getPublic()) {
            throw $this->createNotFoundException();
        }

        return true;
    }
}
