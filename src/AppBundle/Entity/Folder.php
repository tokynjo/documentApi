<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * class Folder, ORM entity for the table my_dossier
 *
 * @ORM\Table(name="my_dossiers")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FolderRepository")
 */
class Folder
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
     * @ORM\Column(name="hash", type="text" )
     */
    private $hash;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateAjout", type="datetime")
     */
    private $addDate;

    /**
     * @ORM\OneToMany(targetEntity="Folder", mappedBy="childFolders", cascade={"persist"})
     */
    private $parentFolder;

    /**
     * @var int
     *
     * @ORM\Column(name="id_folder_user", type="integer", nullable=true)
     */
    private $folderUserId;

    /**
     * @var int
     *
     * @ORM\Column(name="flag", type="integer", nullable=true)
     */
    private $flag;

    /**
     * @var int
     *
     * @ORM\Column(name="id_project", type="integer", nullable=true)
     */
    private $projectId;

    /**
     * @var int
     *
     * @ORM\Column(name="statut", type="integer")
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted_at", type="datetime", length=255, nullable=true)
     */
    private $deletedAt;

    /**
     * @var int
     * @ORM\Column(columnDefinition="TINYINT DEFAULT 0 NOT NULL")
     * @ORM\Column(name="share", type="integer")
     */
    private $share;

    /**
     * @var string
     *
     * @ORM\Column(name="sharePassword", type="string", length=255, nullable=true)
     */
    private $sharePassword;

    /**
     * @var int
     * @ORM\Column(columnDefinition="TINYINT DEFAULT 0 NOT NULL")
     * @ORM\Column(name="crypt", type="integer")
     */
    private $crypt;

    /**
     * @var string
     *
     * @ORM\Column(name="cryptPassword", type="string", length=255, nullable=true)
     */
    private $cryptPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="permalink", type="string", length=255, nullable=true)
     */
    private $permalink;

    /**
     * @var text
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="locked", type="integer")
     */
    private $locked;


    /**
     *
     * @ORM\ManyToOne(targetEntity="Folder", inversedBy="parentFolder", cascade={"persist"})
     * @ORM\JoinColumn(name="dossier_parent", referencedColumnName="id")
     */
    private $childFolders;

    /**
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User", inversedBy="dossiersCreated", cascade={"persist"})
     * @ORM\JoinColumn(name="id_created_by", referencedColumnName="id")
     */
    private $createdBy;

    /**
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User", inversedBy="dossiersDeleted", cascade={"persist"})
     * @ORM\JoinColumn(name="id_deleted_by", referencedColumnName="id")
     */
    private $deletedBy;

    /**
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\File", inversedBy="dossiers", cascade={"persist"})
     * @ORM\JoinColumn(name="id_dossier", referencedColumnName="id")
     */
    private $files;

    /**
     * @ORM\OneToMany(targetEntity="DossierHasUser", mappedBy="dossier", cascade={"persist"})
     */
    private $dossierHasUsers;

    /**
     *
     * @ORM\ManyToOne(targetEntity="InvitationHasUserRequest", inversedBy="dossier", cascade={"persist"})
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
     * Set hash
     *
     * @param string $hash
     *
     * @return Dossiers
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Dossiers
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
     * Set addDate
     *
     * @param \DateTime $addDate
     *
     * @return $this
     */
    public function setAddDate($addDate)
    {
        $this->addDate = $addDate;

        return $this;
    }

    /**
     * Get addDate
     *
     * @return \DateTime
     */
    public function getAddDate()
    {
        return $this->addDate;
    }

    /**
     * Set flag
     *
     * @param integer $flag
     *
     * @return Dossiers
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;

        return $this;
    }

    /**
     * Get flag
     *
     * @return int
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Dossiers
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
     * @return Dossiers
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
     * @return Dossiers
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
     * Set deletedAt
     *
     * @param string $deletedAt
     *
     * @return Dossiers
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return string
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Set share
     *
     * @param integer $share
     *
     * @return Dossiers
     */
    public function setShare($share)
    {
        $this->share = $share;

        return $this;
    }

    /**
     * Get share
     *
     * @return int
     */
    public function getShare()
    {
        return $this->share;
    }

    /**
     * Set sharePassword
     *
     * @param string $sharePassword
     *
     * @return Dossiers
     */
    public function setSharePassword($sharePassword)
    {
        $this->sharePassword = $sharePassword;

        return $this;
    }

    /**
     * Get sharePassword
     *
     * @return string
     */
    public function getSharePassword()
    {
        return $this->sharePassword;
    }

    /**
     * Set crypt
     *
     * @param integer $crypt
     *
     * @return Dossiers
     */
    public function setCrypt($crypt)
    {
        $this->crypt = $crypt;

        return $this;
    }

    /**
     * Get crypt
     *
     * @return int
     */
    public function getCrypt()
    {
        return $this->crypt;
    }

    /**
     * Set cryptPassword
     *
     * @param string $cryptPassword
     *
     * @return Dossiers
     */
    public function setCryptPassword($cryptPassword)
    {
        $this->cryptPassword = $cryptPassword;

        return $this;
    }

    /**
     * Get cryptPassword
     *
     * @return string
     */
    public function getCryptPassword()
    {
        return $this->cryptPassword;
    }

    /**
     * Set permalink
     *
     * @param string $permalink
     *
     * @return Dossiers
     */
    public function setPermalink($permalink)
    {
        $this->permalink = $permalink;

        return $this;
    }

    /**
     * Get permalink
     *
     * @return string
     */
    public function getPermalink()
    {
        return $this->permalink;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Dossiers
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
     * Set locked
     *
     * @param integer $locked
     *
     * @return Dossiers
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;

        return $this;
    }

    /**
     * Get locked
     *
     * @return int
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->parentFolder = new ArrayCollection();
    }

    /**
     * Set dossierEnfants
     *
     * @param \AppBundle\Entity\Dossiers $dossierEnfants
     *
     * @return Dossiers
     */
    public function setDossierEnfants(\AppBundle\Entity\Dossiers $dossierEnfants = null)
    {
        $this->dossierEnfants = $dossierEnfants;

        return $this;
    }

    /**
     * Get dossierEnfants
     *
     * @return \AppBundle\Entity\Dossiers
     */
    public function getDossierEnfants()
    {
        return $this->dossierEnfants;
    }

    /**
     * Get dossierEnfants
     *
     * @return \AppBundle\Entity\Dossiers
     */
    public function setParentFolder()
    {
        return $this->dossierEnfants;
    }



    /**
     * Get parentFolder
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParentFolder()
    {
        return $this->parentFolder;
    }

    /**
     * Set createdBy
     *
     * @param \ApiBundle\Entity\User $createdBy
     *
     * @return Dossiers
     */
    public function setCreatedBy(\ApiBundle\Entity\User $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \ApiBundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set deletedBy
     *
     * @param \ApiBundle\Entity\User $deletedBy
     *
     * @return Dossiers
     */
    public function setDeletedBy(\ApiBundle\Entity\User $deletedBy = null)
    {
        $this->deletedBy = $deletedBy;

        return $this;
    }

    /**
     * Get deletedBy
     *
     * @return \ApiBundle\Entity\User
     */
    public function getDeletedBy()
    {
        return $this->deletedBy;
    }

    /**
     * Add fichier
     *
     * @param \AppBundle\Entity\Client $fichier
     *
     * @return Dossiers
     */
    public function addFichier(\AppBundle\Entity\Client $fichier)
    {
        $this->fichiers[] = $fichier;

        return $this;
    }

    /**
     * Remove fichier
     *
     * @param \AppBundle\Entity\Client $fichier
     */
    public function removeFichier(\AppBundle\Entity\Client $fichier)
    {
        $this->fichiers->removeElement($fichier);
    }

    /**
     * Get fichiers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFichiers()
    {
        return $this->fichiers;
    }

    /**
     * Add dossierHasUser
     *
     * @param \AppBundle\Entity\DossierHasUser $dossierHasUser
     *
     * @return Dossiers
     */
    public function addDossierHasUser(\AppBundle\Entity\DossierHasUser $dossierHasUser)
    {
        $this->dossierHasUsers[] = $dossierHasUser;

        return $this;
    }

    /**
     * Remove dossierHasUser
     *
     * @param \AppBundle\Entity\DossierHasUser $dossierHasUser
     */
    public function removeDossierHasUser(\AppBundle\Entity\DossierHasUser $dossierHasUser)
    {
        $this->dossierHasUsers->removeElement($dossierHasUser);
    }

    /**
     * Get dossierHasUsers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDossierHasUsers()
    {
        return $this->dossierHasUsers;
    }

    /**
     * Set invitationHasUserRequests
     *
     * @param \AppBundle\Entity\InvitationHasUserRequest $invitationHasUserRequests
     *
     * @return Dossiers
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
