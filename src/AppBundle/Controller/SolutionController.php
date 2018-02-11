<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Solution;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

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
            'currentUser' => $this->getUser(),
            'solutions' => $solutions,
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

	/**
	 * @Route("/test", name="test")
	 */
    public function testAction()
    {
	    $pusher = $this->container->get('gos_web_socket.wamp.pusher');
//push(data, route_name, route_arguments)
	    $pusher->push(['my_data' => 'data'], 'app_topic_chat');

	    return new JsonResponse();
    }
}
