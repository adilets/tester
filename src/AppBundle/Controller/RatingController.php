<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class RatingController extends Controller {

    /**
     * @param Request $request
     * @Route("/rating", name="rating")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $rating = $em->getRepository('AppBundle:Solution')->getGeneralRating();

        $perPage = $this->getParameter('rating_per_page');

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate($rating, $request->query->getInt('page', 1), $perPage);

        return $this->render('@App/Rating/index.html.twig', array(
            'pagination' => $pagination,
            'perPage' => $perPage
        ));
    }
}