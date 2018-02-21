<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class file, ORM entity for ths table my_fichiers
 *
 * @ORM\Table(name="my_fichiers")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FileRepository")
 */
class File
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
     * @ORM\Column(name="nom_symbolique", type="string", length=255)
     */
    private $symbolicName;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="taille", type="float")
     */
    private $size;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Folder", inversedBy="files", cascade={"persist"})
     * @ORM\JoinColumn(name="id_dossier", referencedColumnName="id")
     */
    private $folder;

    /**
     * @var integer
     * @ORM\Column(name="id_serveur", type="integer", length=11)
     */
    private $serverId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_upload", type="datetime")
     */
    private $uploadDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiration", type="datetime")
     */
    private $expiration;

    /**
     * @var text
     *
     * @ORM\Column(name="commentaires", type="text", nullable=true)
     */
    private $comment;

    /**
     * @var int
     * @ORM\Column(name="cryptage", type="integer", length=1, options={"default":0})
     */
    private $encryption;

    /**
     * @var string
     *
     * @ORM\Column(name="permalien", type="string", length=255, nullable=true)
     */
    private $permalien;

    /**
     * @var int
     *
     * @ORM\Column(name="statut", type="integer", length=3)
     */
    private $status;

    /**
     * @var int
     *
     * @ORM\Column(name="id_nas", type="integer", length=11, nullable=false)
     */
    private $idNas;
    /**
     * @var string
     *
     * @ORM\Column(name="checksum", type="string", length=255, nullable=true)
     */
    private $checksum;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="deleted_by", type="string", length=255, nullable=true)
     */
    private $deletedBy;

    /**
     * @var int
     *
     * @ORM\Column(name="id_project", type="integer", length=11, nullable=true)
     */
    private $projectId;

    /**
     * @var int
     * @ORM\Column(name="share", type="integer", length=1, options={"default":0})
     */
    private $share;

    /**
     * @var string
     *
     * @ORM\Column(name="sharePassword", type="string", length=255, nullable=true)
     */
    private $sharePassword;

    /**
     * @var string
     *
     * @ORM\Column(name="permalink", type="string", length=100, nullable=true)
     */
    private $permalink;

    /**
     * @var int
     *
     * @ORM\Column(name="favorite", type="integer", length=1, nullable=false)
     */
    private $favorite;

    /**
     * @var int
     *
     * @ORM\Column(name="direct_permalink_enabled", type="integer", length=1, nullable=true)
     */
    private $directPermalinkEnabled;

    /**
     * @var int
     *
     * @ORM\Column(name="locked", type="integer")
     */
    private $locked;

    /**
     * @var int
     *
     * @ORM\Column(name="archive_file_id", type="integer")
     */
    private $archiveFileId;

    /**
     * Creator
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User", inversedBy="files", cascade={"persist"})
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     */
    private $user;


    /**
     * @ORM\OneToMany(targetEntity="FileUser", mappedBy="file", cascade={"persist"})
     */
    private $fileUsers;

    /**
     *
     * @ORM\OneToMany(targetEntity="InvitationRequest", mappedBy="fichier", cascade={"persist"})
     */
    private $invitationRequests;

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
     * @return File
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
     * Set symbolicName
     *
     * @param string $symbolicName
     *
     * @return File
     */
    public function setSymbolicName($symbolicName)
    {
        $this->symbolicName = $symbolicName;

        return $this;
    }

    /**
     * Get symbolicName
     *
     * @return string
     */
    public function getSymbolicName()
    {
        return $this->symbolicName;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return File
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
     * Set size
     *
     * @param float $size
     *
     * @return File
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return float
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set expiration
     *
     * @param \DateTime $expiration
     *
     * @return File
     */
    public function setExpiration($expiration)
    {
        $this->expiration = $expiration;

        return $this;
    }

    /**
     * Get expiration
     *
     * @return \DateTime
     */
    public function getExpiration()
    {
        return $this->expiration;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return File
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set encryption
     *
     * @param string $encryption
     *
     * @return File
     */
    public function setEncryption($encryption)
    {
        $this->encryption = $encryption;

        return $this;
    }

    /**
     * Get encryption
     *
     * @return string
     */
    public function getEncryption()
    {
        return $this->encryption;
    }

    /**
     * Set permalien
     *
     * @param string $permalien
     *
     * @return File
     */
    public function setPermalien($permalien)
    {
        $this->permalien = $permalien;

        return $this;
    }

    /**
     * Get permalien
     *
     * @return string
     */
    public function getPermalien()
    {
        return $this->permalien;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return File
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set checksum
     *
     * @param string $checksum
     *
     * @return File
     */
    public function setChecksum($checksum)
    {
        $this->checksum = $checksum;

        return $this;
    }

    /**
     * Get checksum
     *
     * @return string
     */
    public function getChecksum()
    {
        return $this->checksum;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return File
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
     * @param \DateTime $deletedAt
     *
     * @return File
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Set share
     *
     * @param string $share
     *
     * @return File
     */
    public function setShare($share)
    {
        $this->share = $share;

        return $this;
    }

    /**
     * Get share
     *
     * @return string
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
     * @return File
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
     * Set favorite
     *
     * @param integer $favorite
     *
     * @return File
     */
    public function setFavorite($favorite)
    {
        $this->favorite = $favorite;

        return $this;
    }

    /**
     * Get favorite
     *
     * @return integer
     */
    public function getFavorite()
    {
        return $this->favorite;
    }

    /**
     * Set directPermalinkEnabled
     *
     * @param integer $directPermalinkEnabled
     *
     * @return File
     */
    public function setDirectPermalinkEnabled($directPermalinkEnabled)
    {
        $this->directPermalinkEnabled = $directPermalinkEnabled;

        return $this;
    }

    /**
     * Get directPermalinkEnabled
     *
     * @return integer
     */
    public function getDirectPermalinkEnabled()
    {
        return $this->directPermalinkEnabled;
    }

    /**
     * Set locked
     *
     * @param integer $locked
     *
     * @return File
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;

        return $this;
    }

    /**
     * Get locked
     *
     * @return integer
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * Set folder
     *
     * @param \AppBundle\Entity\Folder $folder
     *
     * @return File
     */
    public function setFolder(\AppBundle\Entity\Folder $folder = null)
    {
        $this->folder = $folder;

        return $this;
    }

    /**
     * Get folder
     *
     * @return \AppBundle\Entity\Folder
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * Set deletedBy
     *
     * @param \ApiBundle\Entity\User $deletedBy
     *
     * @return File
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
     * Set archiveFileId
     *
     * @param integer $archiveFileId
     *
     * @return File
     */
    public function setArchiveFileId($archiveFileId)
    {
        $this->archiveFileId = $archiveFileId;

        return $this;
    }

    /**
     * Get archiveFileId
     *
     * @return integer
     */
    public function getArchiveFileId()
    {
        return $this->archiveFileId;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fileUsers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set user
     *
     * @param \ApiBundle\Entity\User $user
     *
     * @return File
     */
    public function setUser(\ApiBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \ApiBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add fileUser
     *
     * @param \AppBundle\Entity\FileUser $fileUser
     *
     * @return File
     */
    public function addFileUser(\AppBundle\Entity\FileUser $fileUser)
    {
        $this->fileUsers[] = $fileUser;

        return $this;
    }

    /**
     * Remove fileUser
     *
     * @param \AppBundle\Entity\FileUser $fileUser
     */
    public function removeFileUser(\AppBundle\Entity\FileUser $fileUser)
    {
        $this->fileUsers->removeElement($fileUser);
    }

    /**
     * Get fileUsers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFileUsers()
    {
        return $this->fileUsers;
    }

    /**
     * Set invitationHasUserRequests
     *
     * @param \AppBundle\Entity\InvitationHasUserRequest $invitationHasUserRequests
     *
     * @return File
     */
    public function setInvitationRequests(\AppBundle\Entity\InvitationRequest $invitationRequests = null)
    {
        $this->invitationRequests = $invitationRequests;

        return $this;
    }

    /**
     * Get invitationHasUserRequests
     *
     * @return \AppBundle\Entity\InvitationHasUserRequest
     */
    public function getInvitationRequests()
    {
        return $this->invitationRequests;
    }

    /**
     * @return int
     */
    public function getIdNas()
    {
        return $this->idNas;
    }

    /**
     * @param int $idNas
     */
    public function setIdNas($idNas)
    {
        $this->idNas = $idNas;
    }

    /**
     * @return string
     */
    public function getPermalink()
    {
        return $this->permalink;
    }

    /**
     * @param string $permalink
     */
    public function setPermalink($permalink)
    {
        $this->permalink = $permalink;
    }

    /**
     * @return int
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * @param int $projectId
     */
    public function setProjectId($projectId)
    {
        $this->projectId = $projectId;
    }

    /**
     * @return mixed
     */
    public function getServerId()
    {
        return $this->serverId;
    }

    /**
     * @param mixed $serverId
     */
    public function setServerId($serverId)
    {
        $this->serverId = $serverId;
    }

    /**
     * @return \DateTime
     */
    public function getUploadDate()
    {
        return $this->uploadDate;
    }

    /**
     * @param \DateTime $uploadDate
     */
    public function setUploadDate($uploadDate)
    {
        $this->uploadDate = $uploadDate;
    }

    /**
     * @param mixed $fileUsers
     */
    public function setFileUsers($fileUsers)
    {
        $this->fileUsers = $fileUsers;
    }





    /**
     * Add invitationRequest
     *
     * @param \AppBundle\Entity\InvitationRequest $invitationRequest
     *
     * @return File
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
}
