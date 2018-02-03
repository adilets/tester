<?php
namespace AppBundle\Model\Rating;

use AppBundle\Entity\Problem;

class SolvedProblemInfo {
    private $problem;
    private $tryCount;
    private $isAccepted;
    private $sentTime;

    /**
     * SolvedProblemInfo constructor.
     * @param $problem
     */
    public function __construct($problem)
    {
        $this->problem = $problem;
        $this->isAccepted = false;
        $this->sentTime = "";
        $this->tryCount = 0;
    }

    /**
     * @return Problem
     */
    public function getProblem()
    {
        return $this->problem;
    }

    /**
     * @param Problem $problem
     */
    public function setProblem($problem)
    {
        $this->problem = $problem;
    }

    /**
     * @return int
     */
    public function getTryCount()
    {
        return $this->tryCount;
    }

    /**
     * @param int $tryCount
     */
    public function setTryCount($tryCount)
    {
        $this->tryCount = $tryCount;
    }

    /**
     * @return bool
     */
    public function isAccepted()
    {
        return $this->isAccepted;
    }

    /**
     * @param bool $isAccepted
     */
    public function setIsAccepted($isAccepted)
    {
        $this->isAccepted = $isAccepted;
    }

    /**
     * @return string
     */
    public function getSentTime()
    {
        return $this->sentTime;
    }

    /**
     * @param string $sentTime
     */
    public function setSentTime($sentTime)
    {
        $this->sentTime = $sentTime;
    }

    public function incTryCount() {
        $this->tryCount ++;
    }

    /**
     * @return bool
     */
    public function isTriedToSolve() {
        return $this->getTryCount() > 0;
    }

}