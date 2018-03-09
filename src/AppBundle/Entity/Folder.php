<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * class Folder, ORM entity for the table my_dossiers
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
     *
     * @ORM\ManyToOne(targetEntity="Folder", inversedBy="childFolders", cascade={"persist"})
     * @ORM\JoinColumn(name="dossier_parent", referencedColumnName="id")
     */
    private $parentFolder;

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
     * @Gedmo\Timestampable(on="update")
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
     * @var int
     * @ORM\Column(name="crypt", type="integer", length=1, options={"default":0})
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
     * @ORM\OneToMany(targetEntity="Folder", mappedBy="parentFolder", cascade={"persist"})
     */
    private $childFolders;

    /**
     * Owner
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User", inversedBy="folders", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * Creator
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User", inversedBy="myFolders", cascade={"persist"})
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     */

    private $createdBy;

     /**
     * @var string
     *
     * @ORM\Column(name="deleted_by", type="string", length=255, nullable=true)
     */
    private $deletedBy;

    /**
     *
     * @ORM\OneToMany(targetEntity="File", mappedBy="folder", cascade={"persist"})
     * @ORM\JoinColumn(name="id_dossier", referencedColumnName="id")
     */
    private $files;

    /**
     * @ORM\OneToMany(targetEntity="FolderUser", mappedBy="folder", cascade={"persist"})
     */
    private $folderUsers;

    /**
     *
     * @ORM\OneToMany(targetEntity="InvitationRequest", mappedBy="folder", cascade={"persist"})
     */
    private $invitationRequests;

    /**
     * @ORM\OneToMany(targetEntity="News", mappedBy="folder", cascade={"persist"})
     */
    private $news;

    /**
     *
     * @ORM\OneToMany(targetEntity="FolderLog", mappedBy="folder", cascade={"persist"})
     */
    private $folderLogs;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="folder", cascade={"persist"})
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="FileLog", mappedBy="folder", cascade={"persist"})
     */
    private $filesLog;

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
     * @return Folder
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
     * @return Folder
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
     * Set status
     *
     * @param integer $status
     *
     * @return Folder
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
     * @return Folder
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
     * @return Folder
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
     * @return Folder
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
     * @return Folder
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
     * @return Folder
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
     * @return Folder
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
     * @return Folder
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
     * @return Folder
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
     * @return Folder
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
     * @return Folder
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
        $this->childFolders = new ArrayCollection();
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
     * @param mixed $parentFolder
     * @return $this
     */
    public function setParentFolder($parentFolder)
    {
        $this->parentFolder = $parentFolder;
        return $this;
    }


    /**
     * Set deletedBy
     *
     * @param \ApiBundle\Entity\User $deletedBy
     *
     * @return Folder
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
     * Set invitationRequests
     *
     * @param \AppBundle\Entity\InvitationRequest $invitationRequests
     *
     * @return $this
     */
    public function setInvitationRequests(\AppBundle\Entity\InvitationRequest $invitationRequests = null)
    {
        $this->invitationRequests = $invitationRequests;

        return $this;
    }

    /**
     * Get invitationHasUserRequests
     *
     * @return \AppBundle\Entity\InvitationRequest
     */
    public function getInvitationRequests()
    {
        return $this->invitationRequests;
    }

/**
     * Set projectId
     *
     * @param integer $projectId
     *
     * @return Folder
     */
    public function setProjectId($projectId)
    {
        $this->projectId = $projectId;

        return $this;
    }

    /**
     * Get projectId
     *
     * @return integer
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * Get childFolders
     * @return ArrayCollection
     */
    public function getChildFolders()
    {
        return $this->childFolders;
    }

    /**
     * Set files
     *
     * @param \AppBundle\Entity\File $files
     *
     * @return Folder
     */
    public function setFiles(\AppBundle\Entity\File $files = null)
    {
        $this->files = $files;

        return $this;
    }

    /**
     * Get files
     *
     * @return \AppBundle\Entity\File
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Add folderUser
     *
     * @param \AppBundle\Entity\FolderUser $folderUser
     *
     * @return Folder
     */
    public function addFolderUser(FolderUser $folderUser)
    {
        $this->folderUsers[] = $folderUser;

        return $this;
    }

    /**
     * Remove dossierUser
     *
     * @param \AppBundle\Entity\FolderUser $folderUser
     */
    public function removeFolderUser(FolderUser $folderUser)
    {
        $this->folderUsers->removeElement($folderUser);
    }

    /**
     * Get folderUsers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFolderUsers()
    {
        return $this->folderUsers;
    }

    /**
     * Set createdBy
     *
     * @param \ApiBundle\Entity\User $createdBy
     *
     * @return Folder
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
     * Add file
     *
     * @param \AppBundle\Entity\File $file
     *
     * @return Folder
     */
    public function addFile(\AppBundle\Entity\File $file)
    {
        $this->files[] = $file;

        return $this;
    }

    /**
     * Remove file
     *
     * @param \AppBundle\Entity\File $file
     */
    public function removeFile(\AppBundle\Entity\File $file)
    {
        $this->files->removeElement($file);
    }

    /**
     * Add news
     *
     * @param \AppBundle\Entity\News $news
     *
     * @return Folder
     */
    public function addNews(\AppBundle\Entity\News $news)
    {
        $this->news[] = $news;

        return $this;
    }

    /**
     * Remove news
     *
     * @param \AppBundle\Entity\News $news
     */
    public function removeNews(\AppBundle\Entity\News $news)
    {
        $this->news->removeElement($news);
    }

    /**
     * Get news
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * Set user
     *
     * @param \ApiBundle\Entity\User $user
     *
     * @return Folder
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
     * @return mixed
     */
    public function getFilesLog()
    {
        return $this->filesLog;
    }

    /**
     * @param mixed $filesLog
     */
    public function setFilesLog($filesLog)
    {
        $this->filesLog = $filesLog;
    }

    /**
     * Add childFolder
     *
     * @param \AppBundle\Entity\Folder $childFolder
     *
     * @return Folder
     */
    public function addChildFolder(\AppBundle\Entity\Folder $childFolder)
    {
        $this->childFolders[] = $childFolder;

        return $this;
    }

    /**
     * Remove childFolder
     *
     * @param \AppBundle\Entity\Folder $childFolder
     */
    public function removeChildFolder(\AppBundle\Entity\Folder $childFolder)
    {
        $this->childFolders->removeElement($childFolder);
    }

    /**
     * Add folderLog
     *
     * @param \AppBundle\Entity\FolderLog $folderLog
     *
     * @return Folder
     */
    public function addFolderLog(\AppBundle\Entity\FolderLog $folderLog)
    {
        $this->folderLogs[] = $folderLog;

        return $this;
    }

    /**
     * Remove folderLog
     *
     * @param \AppBundle\Entity\FolderLog $folderLog
     */
    public function removeFolderLog(\AppBundle\Entity\FolderLog $folderLog)
    {
        $this->folderLogs->removeElement($folderLog);
    }

    /**
     * Get folderLogs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFolderLogs()
    {
        return $this->folderLogs;
    }

    /**
     * Add comment
     *
     * @param \AppBundle\Entity\Comment $comment
     *
     * @return Folder
     */
    public function addComment(\AppBundle\Entity\Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \AppBundle\Entity\Comment $comment
     */
    public function removeComment(\AppBundle\Entity\Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }
}
