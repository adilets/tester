<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $news = $em->getRepository("AppBundle:News")->findBy([], ['id' => 'DESC']);

        $perPage = $this->getParameter('solution_per_page');

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate($news, $request->query->getInt('page', 1), $perPage);

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @param Request $request
     * @Route("/news/{id}", name="news")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newsAction(Request $request) {

        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $news = $em->getRepository("AppBundle:News")->find($id);

        return $this->render("default/news.html.twig", [
           'news' => $news
        ]);
    }
}
