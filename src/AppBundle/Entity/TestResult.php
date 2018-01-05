<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TestResult
 *
 * @ORM\Table(name="test_result")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TestResultRepository")
 */
class TestResult
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
     * @ORM\Column(name="solution_id", type="integer")
     */
    private $solutionId;

    /**
     * @var int
     *
     * @ORM\Column(name="test", type="integer")
     */
    private $test;

    /**
     * @var int
     *
     * @ORM\Column(name="status_id", type="integer")
     */
    private $statusId;

    /**
     * @var float
     *
     * @ORM\Column(name="time", type="float", nullable=true)
     */
    private $time;

    /**
     * @var string
     *
     * @ORM\Column(name="memory", type="string", length=255, nullable=true)
     */
    private $memory;


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
     * Set solutionId
     *
     * @param integer $solutionId
     *
     * @return TestResult
     */
    public function setSolutionId($solutionId)
    {
        $this->solutionId = $solutionId;

        return $this;
    }

    /**
     * Get solutionId
     *
     * @return int
     */
    public function getSolutionId()
    {
        return $this->solutionId;
    }

    /**
     * Set test
     *
     * @param integer $test
     *
     * @return TestResult
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
     * Set statusId
     *
     * @param integer $statusId
     *
     * @return TestResult
     */
    public function setStatusId($statusId)
    {
        $this->statusId = $statusId;

        return $this;
    }

    /**
     * Get statusId
     *
     * @return int
     */
    public function getStatusId()
    {
        return $this->statusId;
    }

    /**
     * Set time
     *
     * @param float $time
     *
     * @return TestResult
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
     * @param string $memory
     *
     * @return TestResult
     */
    public function setMemory($memory)
    {
        $this->memory = $memory;

        return $this;
    }

    /**
     * Get memory
     *
     * @return string
     */
    public function getMemory()
    {
        return $this->memory;
    }
}

