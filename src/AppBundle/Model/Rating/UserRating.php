<?php

namespace AppBundle\Model\Rating;

use AppBundle\Entity\User;

class UserRating {

    private $user;
    private $solvedProblemCount;
    private $allSentProblemCount;

    /**
     * UserRating constructor.
     * @param User $user
     * @param $solvedProblemCount
     * @param $allSentProblemCount
     */
    public function __construct($user, $solvedProblemCount, $allSentProblemCount)
    {
        $this->user = $user;
        $this->solvedProblemCount = $solvedProblemCount;
        $this->allSentProblemCount = $allSentProblemCount;
    }

    /**
     * @return mixed
     */
    public function getSolvedProblemCount()
    {
        return $this->solvedProblemCount;
    }

    /**
     * @param mixed $solvedProblemCount
     */
    public function setSolvedProblemCount($solvedProblemCount)
    {
        $this->solvedProblemCount = $solvedProblemCount;
    }

    /**
     * @return mixed
     */
    public function getAllSentProblemCount()
    {
        return $this->allSentProblemCount;
    }

    /**
     * @param mixed $allSentProblemCount
     */
    public function setAllSentProblemCount($allSentProblemCount)
    {
        $this->allSentProblemCount = $allSentProblemCount;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }


}