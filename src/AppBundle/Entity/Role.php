<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entity for the table my_role
 * @package AppBundle\Entity
 *
 * @ORM\Table(name="my_role")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RoleRepository")
 */
class Role
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
     * @ORM\Column(name="label", type="string", length=255, nullable=false)
     */
    private $label;

    /**
     * @var text
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\User", mappedBy="role", cascade={"persist"})
     */
    private $users;

/**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

/**
     * get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

/**
     * get description
     * @return text
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * set description
     * @param text $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * get label
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * set label
     *
     * @param string $label
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * Add user
     *
     * @param \ApiBundle\Entity\User $user
     *
     * @return Role
     */
    public function addUser(\ApiBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \ApiBundle\Entity\User $user
     */
    public function removeUser(\ApiBundle\Entity\User $user)
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
}
