<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Right, ORM entity for the table my_right
 * @package AppBundle\Entity
 *
 * @ORM\Table(name="my_right")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RightRepository")
 */
class Right
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;
    /**
     *
     * @ORM\ManyToOne(targetEntity="InvitationHasUserRequest", inversedBy="myRight", cascade={"persist"})
     */
    private $invitationHasUserRequests;


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
     * Set name
     *
     * @param string $name
     *
     * @return MyRight
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set invitationHasUserRequests
     *
     * @param \AppBundle\Entity\InvitationHasUserRequest $invitationHasUserRequests
     *
     * @return MyRight
     */
    public function setInvitationHasUserRequests(\AppBundle\Entity\InvitationHasUserRequest $invitationHasUserRequests = null)
    {
        $this->invitationHasUserRequests = $invitationHasUserRequests;

        return $this;
    }

    /**
     * Get invitationHasUserRequests
     *
     * @return \AppBundle\Entity\InvitationHasUserRequest
     */
    public function getInvitationHasUserRequests()
    {
        return $this->invitationHasUserRequests;
    }
}
