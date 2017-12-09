<?php

namespace AppBundle\Controller;

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
		return $this->render('problem/index.html.twig', [
			"problems" => $problems
		]);
	}

	/**
	 * @Route("/problem/{id}", name="problem")
	 */
	public function problemAction(Request $request, $id, ProblemService $problemService) {
		$em = $this->getDoctrine()->getManager();
		$problem = $em->getRepository("AppBundle:Problem")->find($id);
		$currentUser = $this->getUser();
		$form = $this->createFormBuilder()
			->add('compiler', ChoiceType::class, ['choices' => ["C++" => "c", "Java" => "java", "Python" => "python"]])
			->add("code_input", TextareaType::class, ['label' => false, 'attr' => ['cols' => 80, 'rows' => 30]])
			->add("submit", SubmitType::class)
			->getForm();

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$problemService->check($currentUser, $problem, $form->getData(), $this->get("kernel")->getRootDir());
		}

		return $this->render('problem/problem.html.twig', [
			"problem" => $problem,
			"form" => $form->createView()
		]);
	}
}
