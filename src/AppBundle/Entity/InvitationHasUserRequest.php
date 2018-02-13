<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InvitationHasUserRequest
 *
 * @ORM\Table(name="my_invitation_has_user_request")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InvitationHasUserRequestRepository")
 */
class InvitationHasUserRequest
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
     * @ORM\Column(name="token", type="string", length=255)
     */
    private $token;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var int
     *
     * @ORM\Column(name="synchro", type="integer")
     */
    private $synchro;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="invitationHasUserRequests", cascade={"persist"})
     * @ORM\JoinColumn(name="id_project", referencedColumnName="id")
     */
    private $project;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Dossiers", inversedBy="invitationHasUserRequests", cascade={"persist"})
     * @ORM\JoinColumn(name="id_dossier", referencedColumnName="id")
     */
    private $dossier;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Fichiers", inversedBy="invitationHasUserRequests", cascade={"persist"})
     * @ORM\JoinColumn(name="id_fichier", referencedColumnName="id")
     */
    private $fichier;

    /**
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User", inversedBy="invitationHasUserRequests", cascade={"persist"})
     * @ORM\JoinColumn(name="id_from", referencedColumnName="id")
     */
    private $from;

    /**
     *
     * @ORM\ManyToOne(targetEntity="MyRight", inversedBy="invitationHasUserRequests", cascade={"persist"})
     * @ORM\JoinColumn(name="id_right", referencedColumnName="id")
     */
    private $myRight;

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
     * Set token
     *
     * @param string $token
     *
     * @return InvitationHasUserRequest
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return InvitationHasUserRequest
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return InvitationHasUserRequest
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return InvitationHasUserRequest
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return InvitationHasUserRequest
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set synchro
     *
     * @param integer $synchro
     *
     * @return InvitationHasUserRequest
     */
    public function setSynchro($synchro)
    {
        $this->synchro = $synchro;

        return $this;
    }

    /**
     * Get synchro
     *
     * @return int
     */
    public function getSynchro()
    {
        return $this->synchro;
    }

    /**
     * Set project
     *
     * @param \AppBundle\Entity\Project $project
     *
     * @return InvitationHasUserRequest
     */
    public function setProject(\AppBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \AppBundle\Entity\Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set dossier
     *
     * @param \AppBundle\Entity\Dossiers $dossier
     *
     * @return InvitationHasUserRequest
     */
    public function setDossier(\AppBundle\Entity\Dossiers $dossier = null)
    {
        $this->dossier = $dossier;

        return $this;
    }

    /**
     * Get dossier
     *
     * @return \AppBundle\Entity\Dossiers
     */
    public function getDossier()
    {
        return $this->dossier;
    }

    /**
     * Set fichier
     *
     * @param \AppBundle\Entity\Fichiers $fichier
     *
     * @return InvitationHasUserRequest
     */
    public function setFichier(\AppBundle\Entity\Fichiers $fichier = null)
    {
        $this->fichier = $fichier;

        return $this;
    }

    /**
     * Get fichier
     *
     * @return \AppBundle\Entity\Fichiers
     */
    public function getFichier()
    {
        return $this->fichier;
    }

    /**
     * Set from
     *
     * @param \ApiBundle\Entity\User $from
     *
     * @return InvitationHasUserRequest
     */
    public function setFrom(\ApiBundle\Entity\User $from = null)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Get from
     *
     * @return \ApiBundle\Entity\User
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Set myRight
     *
     * @param \AppBundle\Entity\MyRight $myRight
     *
     * @return InvitationHasUserRequest
     */
    public function setMyRight(\AppBundle\Entity\MyRight $myRight = null)
    {
        $this->myRight = $myRight;

        return $this;
    }

    /**
     * Get myRight
     *
     * @return \AppBundle\Entity\MyRight
     */
    public function getMyRight()
    {
        return $this->myRight;
    }
}
