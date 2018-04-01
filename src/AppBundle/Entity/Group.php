<?php
// src/AppBundle/Entity/Group.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_group")
 */
class Group extends BaseGroup
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\University")
     * @ORM\JoinColumn(name="university_id", referencedColumnName="id")
     */
    private $university;

    public function __construct($name = null)
    {
        parent::__construct($name);
        // your own logic
    }

    public function __toString()
    {
        return $this->name ? $this->name : '';
    }

    /**
     * Set university
     *
     * @param \AppBundle\Entity\University $university
     *
     * @return Group
     */
    public function setUniversity(\AppBundle\Entity\University $university = null)
    {
        $this->university = $university;

        return $this;
    }

    /**
     * Get university
     *
     * @return \AppBundle\Entity\University
     */
    public function getUniversity()
    {
        return $this->university;
    }
}
