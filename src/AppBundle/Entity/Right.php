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
     * Privilege folders
     * @ORM\OneToMany(targetEntity="FolderUser", mappedBy="right", cascade={"persist"})
     */

    private $folders;

    /**
     * Privilege files
     * @ORM\OneToMany(targetEntity="FileUser", mappedBy="right", cascade={"persist"})
     */

    private $fileUser;

    /**
     * Project Right
     * @ORM\OneToMany(targetEntity="ProjectUser", mappedBy="right", cascade={"persist"})
     */

    private $projectUser;

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
     * Set $invitationRequests
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

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->invitationRequests = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add invitationRequest
     *
     * @param \AppBundle\Entity\InvitationRequest $invitationRequest
     *
     * @return Right
     */
    public function addInvitationRequest(\AppBundle\Entity\InvitationRequest $invitationRequest)
    {
        $this->invitationRequests[] = $invitationRequest;

        return $this;
    }

    /**
     * Remove invitationRequest
     *
     * @param \AppBundle\Entity\InvitationRequest $invitationRequest
     */
    public function removeInvitationRequest(\AppBundle\Entity\InvitationRequest $invitationRequest)
    {
        $this->invitationRequests->removeElement($invitationRequest);
    }

    /**
     * Get invitationRequests
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInvitationRequests()
    {
        return $this->invitationRequests;
    }

    /**
     * Add fileUser
     *
     * @param \AppBundle\Entity\FileUser $fileUser
     *
     * @return Right
     */
    public function addFileUser(\AppBundle\Entity\FileUser $fileUser)
    {
        $this->fileUser[] = $fileUser;

        return $this;
    }

    /**
     * Remove fileUser
     *
     * @param \AppBundle\Entity\FileUser $fileUser
     */
    public function removeFileUser(\AppBundle\Entity\FileUser $fileUser)
    {
        $this->fileUser->removeElement($fileUser);
    }

    /**
     * Get fileUser
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFileUser()
    {
        return $this->fileUser;
    }

    /**
     * Add projectUser
     *
     * @param \AppBundle\Entity\ProjectUser $projectUser
     *
     * @return Right
     */
    public function addProjectUser(\AppBundle\Entity\ProjectUser $projectUser)
    {
        $this->projectUser[] = $projectUser;

        return $this;
    }

    /**
     * Remove projectUser
     *
     * @param \AppBundle\Entity\ProjectUser $projectUser
     */
    public function removeProjectUser(\AppBundle\Entity\ProjectUser $projectUser)
    {
        $this->projectUser->removeElement($projectUser);
    }

    /**
     * Get projectUser
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProjectUser()
    {
        return $this->projectUser;
    }
}
