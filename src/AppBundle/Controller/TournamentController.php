<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Problem;
use AppBundle\Entity\Tournament;
use AppBundle\Entity\User;
use AppBundle\Model\Rating\TournamentRating;
use AppBundle\Model\Rating\SolvedProblemInfo;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

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
    public function tournamentSentAction(Tournament $tournament, Request $request) {

        $em = $this->getDoctrine()->getManager();

        $solutions = $em->getRepository('AppBundle:Solution')->findBy(['tournament' => $tournament->getId()], ['createdAt' => 'DESC']);

        $perPage = $this->getParameter('solution_per_page');

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate($solutions, $request->query->getInt('page', 1), $perPage);

        return $this->render('AppBundle:Tournament:sent.html.html.twig', array(
            'tournament' => $tournament,
            'pagination' => $pagination
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

        $result = $em->getRepository('AppBundle:Solution')->getTournamentRating($tournament);
        $rating = array();

        /**
         * @var User $user
         * @var Problem $problem
         * @var TournamentRating[] $rating
         */
        foreach ($tournament->getUsers() as $user) {
            $rating[$user->getId()] = new TournamentRating($user);
            foreach ($tournament->getProblems() as $problem) {
                $rating[$user->getId()]->addSolvedProblem(new SolvedProblemInfo($problem));
            }
        }

        foreach ($result as $item) {
            $ratingRow = $rating[$item['user_id']];
            $solvedProblemInfo = $ratingRow->getSolvedProblem($item['problem_id']);
            if ($item['status_id'] == 1) {
                $sentDate = new DateTime($item['sent_time']);
                $sentDiffSecond = $this->getDateDiffInSecond($tournament->getStart(), $sentDate);
                $rating[$item['user_id']]->addSpentTime($sentDiffSecond);
                $solvedProblemInfo->setIsAccepted(true);
                $solvedProblemInfo->setSentTime($this->getDateDiffString($tournament->getStart(), $sentDate));
                $ratingRow->incSolvedCount();
            } elseif (!$solvedProblemInfo->isAccepted()) {
                $solvedProblemInfo->incTryCount();
            }
        }

        uasort($rating, function ($value1, $value2) {
            /**
             * @var TournamentRating $value1
             * @var TournamentRating $value2
             */
            if (
                $value1->getSolvedCount() > $value2->getSolvedCount() ||
                (
                    $value1->getSolvedCount() == $value2->getSolvedCount() &&
                    $value1->getSpentTime() < $value2->getSpentTime())
                )
            {
                return -1;
            }
            return 1;
        });

        return $this->render('AppBundle:Tournament:rating.html.twig', array(
            'tournament' => $tournament,
            'rating' => $rating
        ));
    }

    /**
     *
     * @Route("/tournament/join/{id}", name="tournamentJoin")
     * @ParamConverter("post", class="AppBundle:Tournament")
     *
     * @param Tournament $tournament
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tournamentJoinAction(Tournament $tournament) {
        if (!$tournament->hasAccess($this->getUser())) {
            $em = $this->getDoctrine()->getManager();
            $tournament->addUser($this->getUser());
            $em->persist($tournament);
            $em->flush();
        }
        return $this->redirectToRoute("tournamentRating", ['id' => $tournament->getId()]);
    }

    private function getDateDiffInSecond(DateTime $firstDate, DateTime $secondDate) {
        return ($secondDate->getTimestamp() - $firstDate->getTimestamp());
    }

    /**
     * @param DateTime $firstDate
     * @param DateTime $secondDate
     * @return string
     */
    private function getDateDiffString(DateTime $firstDate, DateTime $secondDate) {
        $diff = $firstDate->diff($secondDate);
        $diffString = "";
        if ($diff->days > 0) {
            $diffString .= $diff->days . "d ";
        }
        $diffString .= $diff->h . ":";
        if ($diff->i < 10) {
            $diffString .= "0";
        }
        $diffString .= $diff->i . ":";
        if ($diff->s < 10) {
            $diffString .= "0";
        }
        $diffString .= $diff->s;
        return $diffString;
    }
}
