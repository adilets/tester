<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
	public function problemAction(Request $request, $id) {
		$em = $this->getDoctrine()->getManager();
		$problem = $em->getRepository("AppBundle:Problem")->find($id);

		$form = $this->createFormBuilder()
			->add('compilers', ChoiceType::class, ['choices' => ["C++" => "c", "Java" => "java", "Python" => "python"]])
			->add("code_input", TextareaType::class, ['label' => false, 'attr' => ['cols' => 80, 'rows' => 30]])
			->add("submit", SubmitType::class)
			->getForm();

		return $this->render('problem/problem.html.twig', [
			"problem" => $problem,
			"form" => $form->createView()
		]);
	}
}
