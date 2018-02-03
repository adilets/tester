<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tournament
 *
 * @ORM\Table(name="tournament")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TournamentRepository")
 */
class Tournament
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="is_public", type="boolean")
     */
    private $isPublic;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start", type="datetime")
     */
    private $start;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end", type="datetime")
     */
    private $end;

    /**
     * @ORM\ManyToMany(targetEntity="Problem")
     * @ORM\JoinTable(name="tournaments_problems",
     *      joinColumns={@ORM\JoinColumn(name="tournament_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="problem_id", referencedColumnName="id")}
     * )
     */
    private $problems;

    /**
     * @ORM\ManyToMany(targetEntity="User")
     * @ORM\JoinTable(name="tournaments_users",
     *      joinColumns={@ORM\JoinColumn(name="tournament_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     */
    private $users;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->problems = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title
     *
     * @param string $title
     *
     * @return Tournament
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Tournament
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set start
     *
     * @param \DateTime $start
     *
     * @return Tournament
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param \DateTime $end
     *
     * @return Tournament
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Add problem
     *
     * @param \AppBundle\Entity\Problem $problem
     *
     * @return Tournament
     */
    public function addProblem(\AppBundle\Entity\Problem $problem)
    {
        $this->problems[] = $problem;

        return $this;
    }

    /**
     * Remove problem
     *
     * @param \AppBundle\Entity\Problem $problem
     */
    public function removeProblem(\AppBundle\Entity\Problem $problem)
    {
        $this->problems->removeElement($problem);
    }

    /**
     * Get problems
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProblems()
    {
        return $this->problems;
    }

    /**
     * Add user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Tournament
     */
    public function addUser(\AppBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \AppBundle\Entity\User $user
     */
    public function removeUser(\AppBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @return bool
     */
    public function isNotStarted()
    {
        $now = new \DateTime();
        return $this->getStart() > $now;
    }

    /**
     * @return bool
     */
    public function isRunning()
    {
        $now = new \DateTime();
        return $now >= $this->getStart() && $now <= $this->getEnd();
    }

    /**
     * @return bool
     */
    public function isFinished()
    {
        $now = new \DateTime();
        return $now > $this->getEnd();
    }

    /**
     * @param User $user
     * @return bool
     */
    public function hasAccess(User $user) {
        foreach ($this->users as $u) {
            if ($user == $u) {
                return true;
            }
        }
        return false;
    }

    /**
     * Set isPublic
     *
     * @param boolean $isPublic
     *
     * @return Tournament
     */
    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    /**
     * Get isPublic
     *
     * @return boolean
     */
    public function getIsPublic()
    {
        return $this->isPublic;
    }
}
