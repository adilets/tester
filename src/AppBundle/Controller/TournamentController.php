<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Tournament;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class TournamentController extends Controller
{
    /**
     * @Route("/tournaments", name="tournaments")
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
     * @Route("/tournament/{id}", name="tournament")
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

    /**
     *
     * @Route("/tournament/sent/{id}", name="tournamentSent")
     * @ParamConverter("post", class="AppBundle:Tournament")
     *
     * @param Tournament $tournament
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tournamentSentAction(Tournament $tournament) {

        $em = $this->getDoctrine()->getManager();

        $solutions = $em->getRepository('AppBundle:Solution')->findBy(['tournament' => $tournament->getId()], ['createdAt' => 'DESC']);

        return $this->render('AppBundle:Tournament:sent.html.html.twig', array(
            'tournament' => $tournament,
            'solutions' => $solutions
        ));
    }

    /**
     *
     * @Route("/tournament/rating/{id}", name="tournamentRating")
     * @ParamConverter("post", class="AppBundle:Tournament")
     *
     * @param Tournament $tournament
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tournamentRatingAction(Tournament $tournament) {

        $em = $this->getDoctrine()->getManager();

        $result = $em->getRepository('AppBundle:Solution')->getRating($tournament);
        $rating = array();
        $totalTimes = array();
        foreach ($tournament->getUsers() as $user) {
            foreach ($tournament->getProblems() as $problem) {
                $rating[$user->getId()][$problem->getId()] =  array(
                    'count' => 0,
                    'accepted' => false,
                    'sentTime' => 0
                );
            }
            $totalTimes[$user->getId()] = 0;
        }

        foreach ($result as $item) {
            if ($item['status_id'] == 1) {
                $sentDate = new DateTime($item['sent_time']);
                $sentDiffSecond = $this->getDateDiffInSecond($tournament->getStart(), $sentDate);
                $totalTimes[$item['user_id']] += $sentDiffSecond;
                $rating[$item['user_id']][$item['problem_id']]['accepted'] = true;
                $diff = $tournament->getStart()->diff($sentDate);
                $rating[$item['user_id']][$item['problem_id']]['sentTime'] = $diff->days . "d " . $diff->h . ":" . $diff->i . ":" . $diff->s;
            } elseif (!$rating[$item['user_id']][$item['problem_id']]['accepted']) {
                $rating[$item['user_id']][$item['problem_id']]['count'] += $item['count'];
            }
        }
//        dump($rating);die();
        return $this->render('AppBundle:Tournament:rating.html.twig', array(
            'totalTimes' => $totalTimes,
            'tournament' => $tournament,
            'rating' => $rating
        ));
    }

    private function getDateDiffInSecond(DateTime $firstDate, DateTime $secondDate) {
        return ($secondDate->getTimestamp() - $firstDate->getTimestamp());
    }

}
