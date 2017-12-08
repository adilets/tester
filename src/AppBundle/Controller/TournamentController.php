<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Tournament;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class TournamentController extends Controller
{
    /**
     * @Route("/tournaments")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tournaments = $em->getRepository('AppBundle:Tournament')->findAll();

        return $this->render('AppBundle:Tournament:index.html.twig', [
            'tournaments' => $tournaments
        ]);
    }

    /**
     *
     * @Route("/tournament/{id}")
     * @ParamConverter("post", class="AppBundle:Tournament")
     *
     * @param Tournament $tournament
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tournamentAction(Tournament $tournament)
    {
        return $this->render('AppBundle:Tournament:tournament.html.twig', array(
            'tournament' => $tournament
        ));
    }

}
