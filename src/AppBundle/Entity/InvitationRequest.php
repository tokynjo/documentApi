<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity InvitationRequest
 * @package AppBundle\Entity
 *
 * @ORM\Table(name="my_invitation_request")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InvitationRequestRepository")
 */
class InvitationRequest
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
     *
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="invitations", cascade={"persist"})
     * @ORM\JoinColumn(name="id_project", referencedColumnName="id")
     */
    private $project;

    /**
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User", inversedBy="invitationsSent", cascade={"persist"})
     * @ORM\JoinColumn(name="id_from", referencedColumnName="id")
     */
    private $from;


    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Right", inversedBy="invitationRequest", cascade={"persist"})
     * @ORM\JoinColumn(name="id_right", referencedColumnName="id")
     */
    private $right;

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
     *
     * @ORM\ManyToOne(targetEntity="Dossiers", inversedBy="invitationsRequests", cascade={"persist"})
     * @ORM\JoinColumn(name="id_dossier", referencedColumnName="id")
     */
    private $dossier;
    /**
     *
     * @ORM\ManyToOne(targetEntity="Fichiers", inversedBy="invitationsRequests", cascade={"persist"})
     * @ORM\JoinColumn(name="id_fichier", referencedColumnName="id")
     */
    private $fichier;

    /**
     * @var string
     *
     * @ORM\Column(name="synchro", type="string", length=255)
     */
    private $synchro;


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
     * @return $this
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
     * @return $this
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
     * @return $this
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
     * @return $this
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
     * @return $this
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
     * @return $this
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
     * @return $this
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
     * @return $this
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
     * @return $this
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
     * @return $this
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
     * @param Right $right
     *
     * @return $this
     */
    public function setMyRight(Right $right = null)
    {
        $this->right = $right;

        return $this;
    }

    /**
     * Get right
     *
     * @return Right
     */
    public function getRight()
    {
        return $this->right;
    }
}
