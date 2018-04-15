<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Solution;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SolutionController extends Controller
{
    /**
     * @Route("/solutions", name="solutions")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $solutions = $em->getRepository('AppBundle:Solution')->findBy([], ['id' => 'DESC']);

        $perPage = $this->getParameter('solution_per_page');

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate($solutions, $request->query->getInt('page', 1), $perPage);

        return $this->render('@App/Solution/index.html.twig', array(
            'pagination' => $pagination,
        ));
    }

    /**
     * @Route("/solution/{id}", name="solution")
     * @Method("GET")
     * @param Solution $solution
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function solutionAction(Solution $solution)
    {
        $em = $this->getDoctrine()->getManager();

        $testResults = $em->getRepository('AppBundle:TestResult')->findBy(['solution' => $solution]);

        return $this->render('@App/Solution/solution.html.twig', array(
            'solution' => $solution,
            'testResults' => $testResults,
        ));
    }
}
