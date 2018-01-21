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
	 * @var User
	 *
	 * @ORM\ManyToOne(targetEntity="User")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 */
    private $user;

	/**
	 * @var Problem
	 *
	 * @ORM\ManyToOne(targetEntity="Problem")
	 * @ORM\JoinColumn(name="problem_id", referencedColumnName="id")
	 */
    private $problem;

	/**
	 * @var Language
	 *
	 * @ORM\ManyToOne(targetEntity="Language")
	 * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
	 */
    private $language;

    /**
     * @var Status
     *
     * @ORM\ManyToOne(targetEntity="Status")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
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
     * @var integer
     *
     * @ORM\Column(name="memory", type="integer")
     */
    private $memory = 0;

	/**
	 * @var Tournament
	 *
	 * @ORM\ManyToOne(targetEntity="Tournament")
	 * @ORM\JoinColumn(name="tournament_id", referencedColumnName="id")
	 */
    private $tournament;

    /**
     * @var string
     *
     * @ORM\Column(name="source_code", type="text", nullable=true)
     */
    private $sourceCode;

    /**
     * @var string
     *
     * @ORM\Column(name="compiler_message", type="text", nullable=true)
     */
    private $compilerMessage;

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
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
     * @return integer
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
     * @return integer
     */
    public function getMemory()
    {
        return $this->memory;
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
        $this->sourceCode = $sourceCode;

        return $this;
    }

    /**
     * Get sourceCode
     *
     * @return string
     */
    public function getSourceCode()
    {
        return $this->sourceCode;
    }

    /**
     * Set compilerMessage
     *
     * @param string $compilerMessage
     *
     * @return Solution
     */
    public function setCompilerMessage($compilerMessage)
    {
        $this->compilerMessage = $compilerMessage;

        return $this;
    }

    /**
     * Get compilerMessage
     *
     * @return string
     */
    public function getCompilerMessage()
    {
        return $this->compilerMessage;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Solution
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set problem
     *
     * @param \AppBundle\Entity\Problem $problem
     *
     * @return Solution
     */
    public function setProblem(\AppBundle\Entity\Problem $problem = null)
    {
        $this->problem = $problem;

        return $this;
    }

    /**
     * Get problem
     *
     * @return \AppBundle\Entity\Problem
     */
    public function getProblem()
    {
        return $this->problem;
    }

    /**
     * Set language
     *
     * @param \AppBundle\Entity\Language $language
     *
     * @return Solution
     */
    public function setLanguage(\AppBundle\Entity\Language $language = null)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return \AppBundle\Entity\Language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set status
     *
     * @param \AppBundle\Entity\Status $status
     *
     * @return Solution
     */
    public function setStatus(\AppBundle\Entity\Status $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \AppBundle\Entity\Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set tournament
     *
     * @param \AppBundle\Entity\Tournament $tournament
     *
     * @return Solution
     */
    public function setTournament(\AppBundle\Entity\Tournament $tournament = null)
    {
        $this->tournament = $tournament;

        return $this;
    }

    /**
     * Get tournament
     *
     * @return \AppBundle\Entity\Tournament
     */
    public function getTournament()
    {
        return $this->tournament;
    }
}
