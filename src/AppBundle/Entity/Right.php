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
     * @ORM\OneToMany(targetEntity="InvitationRequest", mappedBy="right", cascade={"persist"})
     */
    private $invitationRequests;

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
     * @param \AppBundle\Entity\InvitationRequest $invitationRequests
     *
     * @return MyRight
     */
    public function setInvitationRequests(\AppBundle\Entity\InvitationRequest $invitationRequests = null)
    {
        $this->invitationRequests = $invitationRequests;

        return $this;
    }

    /**
     * Get invitationRequests
     *
     * @return \AppBundle\Entity\InvitationRequest
     */
    public function getInvitationHasUserRequests()
    {
        return $this->invitationRequests;
    }
}
