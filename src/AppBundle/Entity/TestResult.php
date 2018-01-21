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
     * @var Solution
     *
     * @ORM\ManyToOne(targetEntity="Solution")
     * @ORM\JoinColumn(name="solution_id", referencedColumnName="id")
     */
    private $solution;

    /**
     * @var int
     *
     * @ORM\Column(name="test", type="integer")
     */
    private $test;

    /**
     * @var Status
     *
     * @ORM\ManyToOne(targetEntity="Status")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    private $status;

    /**
     * @var float
     *
     * @ORM\Column(name="time", type="float", nullable=true)
     */
    private $time;

    /**
     * @var integer
     *
     * @ORM\Column(name="memory", type="integer")
     */
    private $memory = 0;


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
     * @return integer
     */
    public function getTest()
    {
        return $this->test;
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
     * @param integer $memory
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
     * @return integer
     */
    public function getMemory()
    {
        return $this->memory;
    }

    /**
     * Set solution
     *
     * @param \AppBundle\Entity\Solution $solution
     *
     * @return TestResult
     */
    public function setSolution(\AppBundle\Entity\Solution $solution = null)
    {
        $this->solution = $solution;

        return $this;
    }

    /**
     * Get solution
     *
     * @return \AppBundle\Entity\Solution
     */
    public function getSolution()
    {
        return $this->solution;
    }

    /**
     * Set status
     *
     * @param \AppBundle\Entity\Status $status
     *
     * @return TestResult
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
}
