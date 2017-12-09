<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Status
 *
 * @ORM\Table(name="solution")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StatusRepository")
 */
class Solution
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;

    /**
     * @var int
     *
     * @ORM\Column(name="problem_id", type="integer")
     */
    private $problemId;

    /**
     * @var int
     *
     * @ORM\Column(name="language_id", type="integer")
     */
    private $languageId;

    /**
     * @var string
     *
     * @ORM\Column(name="status_id", type="integer")
     */
    private $status;

    /**
     * @var int
     *
     * @ORM\Column(name="test", type="integer", nullable=true)
     */
    private $test;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var float
     *
     * @ORM\Column(name="time", type="float")
     */
    private $time;

    /**
     * @var int
     *
     * @ORM\Column(name="memory", type="integer", nullable=true)
     */
    private $memory;

    /**
     * @var int
     *
     * @ORM\Column(name="tournament_id", type="integer", nullable=true)
     */
    private $tournament_id;

    /**
     * @var string
     *
     * @ORM\Column(name="source_code", type="text", nullable=true)
     */
    private $source_code;

    /**
     * Solution constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Solution
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set problemId
     *
     * @param integer $problemId
     *
     * @return Solution
     */
    public function setProblemId($problemId)
    {
        $this->problemId = $problemId;

        return $this;
    }

    /**
     * Get problemId
     *
     * @return int
     */
    public function getProblemId()
    {
        return $this->problemId;
    }

    /**
     * Set languageId
     *
     * @param integer $languageId
     *
     * @return Solution
     */
    public function setLanguageId($languageId)
    {
        $this->languageId = $languageId;

        return $this;
    }

    /**
     * Get languageId
     *
     * @return int
     */
    public function getLanguageId()
    {
        return $this->languageId;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Solution
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set test
     *
     * @param integer $test
     *
     * @return Solution
     */
    public function setTest($test)
    {
        $this->test = $test;

        return $this;
    }

    /**
     * Get test
     *
     * @return int
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Solution
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set time
     *
     * @param float $time
     *
     * @return Solution
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return float
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set memory
     *
     * @param integer $memory
     *
     * @return Solution
     */
    public function setMemory($memory)
    {
        $this->memory = $memory;

        return $this;
    }

    /**
     * Get memory
     *
     * @return int
     */
    public function getMemory()
    {
        return $this->memory;
    }

    /**
     * Get tournament_id
     *
     * @return int
     */
    public function getTournamentId()
    {
        return $this->tournament_id;
    }

    /**
     * Set tournament_id
     *
     * @param integer $tournament_id
     *
     * @return Solution
     */
    public function setTournamentId($tournament_id)
    {
        $this->tournament_id = $tournament_id;

        return $this;
    }

    /**
     * Set sourceCode
     *
     * @param string $sourceCode
     *
     * @return Solution
     */
    public function setSourceCode($sourceCode)
    {
        $this->source_code = $sourceCode;

        return $this;
    }

    /**
     * Get sourceCode
     *
     * @return string
     */
    public function getSourceCode()
    {
        return $this->source_code;
    }
}
