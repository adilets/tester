<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Solution;
use AppBundle\Entity\Status;
use AppBundle\Entity\User;
use AppBundle\Service\Problem as ProblemService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;

class ProblemController extends Controller
{
	/**
	 * @Route("/problems", name="problems")
	 */
	public function indexAction(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$problems = $em->getRepository("AppBundle:Problem")->findBy(["public" => true]);
		return $this->render('AppBundle:Problem:index.html.twig', [
			"problems" => $problems
		]);
	}

	/**
	 * @Route("/problem/{id}", name="problem")
	 */
	public function problemAction(Request $request, $id, ProblemService $problemService) {
		$em = $this->getDoctrine()->getManager();
		$problem = $em->getRepository("AppBundle:Problem")->find($id);
		/** @var User $currentUser */
		$currentUser = $this->getUser();
		$form = $this->createFormBuilder()
			->add('compiler', ChoiceType::class, ['choices' => ["C++" => "c", "Java" => "java", "Python" => "python"]])
			->add("code_input", TextareaType::class, ['label' => false, 'attr' => ['cols' => 80, 'rows' => 30]])
			->add("submit", SubmitType::class)
			->getForm();

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
		    $waitingStatus = $em->getRepository("AppBundle:Status")->findOneBy(["title" => "Waiting"]);

		    $solution = new Solution();
		    $solution->setProblemId($problem->getId());
		    $solution->setLanguageId(1); //TODO after creating language entity. While "1"
            $solution->setUserId($currentUser->getId());
            $solution->setSourceCode($form->get('code_input'));
            $solution->setStatus($waitingStatus->getId());
            $em->persist($solution);
            $em->flush();

//			$this->redirectToRoute()
		}

		return $this->render('AppBundle:Problem:problem.html.twig', [
			"problem" => $problem,
			"form" => $form->createView()
		]);
	}
}
