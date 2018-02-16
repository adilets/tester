<?php
namespace AppBundle\Model\Rating;

class TournamentRating {
    private $user;
    private $solvedProblems;
    private $spentTime;
    private $solvedCount;

    /**
     * Rating constructor.
     * @param \AppBundle\Entity\User $user
     */
    public function __construct($user)
    {
        $this->user = $user;
        $this->solvedProblems = [];
        $this->spentTime = 0;
        $this->solvedCount = 0;
    }

    /**
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param \AppBundle\Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \AppBundle\Model\Rating\SolvedProblemInfo[]
     */
    public function getSolvedProblems()
    {
        return $this->solvedProblems;
    }

    /**
     * @param \AppBundle\Model\Rating\SolvedProblemInfo[] $solvedProblems
     */
    public function setSolvedProblems($solvedProblems)
    {
        $this->solvedProblems = $solvedProblems;
    }

    /**
     * @param \AppBundle\Model\Rating\SolvedProblemInfo $solvedProblemInfo
     */
    public function addSolvedProblem(SolvedProblemInfo $solvedProblemInfo) {
        $this->solvedProblems[$solvedProblemInfo->getProblem()->getId()] = $solvedProblemInfo;
    }

    /**
     * @param $problemId
     * @return \AppBundle\Model\Rating\SolvedProblemInfo
     */
    public function getSolvedProblem($problemId) {
        return $this->solvedProblems[$problemId];
    }

    /**
     * @return int
     */
    public function getSpentTime()
    {
        return $this->spentTime;
    }

    public function getTotalTime() {
        return round($this->getSpentTime() / 60);
    }

    /**
     * @return int
     */
    public function getSolvedCount()
    {
        return $this->solvedCount;
    }

    /**
     * @param int $solvedCount
     */
    public function setSolvedCount($solvedCount)
    {
        $this->solvedCount = $solvedCount;
    }

    public function incSolvedCount() {
        $this->solvedCount ++;
    }

    /**
     * @param int $spentTime
     */
    public function setSpentTime($spentTime)
    {
        $this->spentTime = $spentTime;
    }

    /**
     * @param int $spentTime
     */
    public function addSpentTime($spentTime) {
        $this->spentTime += $spentTime;
    }
}