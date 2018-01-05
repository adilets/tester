<?php

namespace AppBundle\Controller;

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
            'solutions' => $solutions,
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