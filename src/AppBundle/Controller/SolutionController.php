<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SolutionController extends Controller
{
    /**
     * @Route("/solutions", name="solutions")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $solutions = $em->getRepository('AppBundle:Solution')->findBy([], ['id' => 'DESC']);

        return $this->render('@App/Solution/index.html.twig', array(
            'solutions' => $solutions,
        ));
    }
}
