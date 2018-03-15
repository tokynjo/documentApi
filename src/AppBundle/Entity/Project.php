<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entity Project
 * @package AppBundle\Entity
 *
 * @ORM\Table(name="my_project")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectRepository")
 */
class Project
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
     *
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="projects", cascade={"persist"})
     * @ORM\JoinColumn(name="id_client", referencedColumnName="id")
     */
    private $client;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;

    /**
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User", inversedBy="projectsUser", cascade={"persist"})
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     */
    private $user;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expired_at", type="datetime", nullable=true)
     */
    private $expiredAt;

    /**
     * @var string
     *
     * @ORM\Column(name="permalink", type="string", length=255, nullable=true)
     */
    private $permalink;

    /**
     * @var int
     *
     * @ORM\Column(name="share", type="integer")
     */
    private $share;

    /**
     * @var string
     *
     * @ORM\Column(name="share_password", type="string", length=255, nullable=true)
     */
    private $sharePassword;

    /**
     * @var string
     *
     * @ORM\Column(name="icon", type="string", length=255, nullable=true)
     */
    private $icon;

    /**
     * @var int
     *
     * @ORM\Column(name="order_by_position", type="integer")
     */
    private $orderByPosition;

    /**
     *
     * @ORM\OneToMany(targetEntity="InvitationRequest", mappedBy="project", cascade={"persist"})
     */
    private $invitationRequests;

    /**
     * @ORM\OneToMany(targetEntity="News", mappedBy="project", cascade={"persist"})
     */
    private $news;

    /**
     * @ORM\OneToMany(targetEntity="ProjectUser" , mappedBy="project" , cascade={"all"})
     *
     */
    private $projectUsers;


    public function __construct()
    {
        $this->news = new ArrayCollection();
        $this->invitationRequests = new ArrayCollection();
        $this->projectUsers = new ArrayCollection();
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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Project
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Project
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
     * Set description
     *
     * @param string $description
     *
     * @return Project
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
     * Set createrAt
     *
     * @param \DateTime $createrAt
     *
     * @return Project
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
    public function getcreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set expiredAt
     *
     * @param \DateTime $expiredAt
     *
     * @return Project
     */
    public function setExpiredAt($expiredAt)
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }

    /**
     * Get expiredAt
     *
     * @return \DateTime
     */
    public function getExpiredAt()
    {
        return $this->expiredAt;
    }

    /**
     * Set permalink
     *
     * @param string $permalink
     *
     * @return Project
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
     * Set share
     *
     * @param integer $share
     *
     * @return Project
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
     * @return Project
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
     * Set icon
     *
     * @param string $icon
     *
     * @return Project
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set orderByPosition
     *
     * @param integer $orderByPosition
     *
     * @return Project
     */
    public function setOrderByPosition($orderByPosition)
    {
        $this->orderByPosition = $orderByPosition;

        return $this;
    }

    /**
     * Get orderByPosition
     *
     * @return int
     */
    public function getOrderByPosition()
    {
        return $this->orderByPosition;
    }

    /**
     * Set client
     *
     * @param \AppBundle\Entity\Client $client
     *
     * @return Project
     */
    public function setClient(\AppBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \AppBundle\Entity\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set user
     *
     * @param \ApiBundle\Entity\User $user
     *
     * @return Project
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
     * Set $invitationRequests
     *
     * @param \AppBundle\Entity\InvitationRequest $invitationRequests
     *
     * @return Project
     */
    public function setInvitationHasUserRequests(\AppBundle\Entity\InvitationRequest $invitationRequests = null)
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
     * @return mixed
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * @param mixed $news
     */
    public function setNews($news)
    {
        $this->news = $news;
    }

    /**
     * Add invitationRequest
     *
     * @param \AppBundle\Entity\InvitationRequest $invitationRequest
     *
     * @return Project
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
     * Add news
     *
     * @param \AppBundle\Entity\News $news
     *
     * @return Project
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
     * Add invitation
     *
     * @param \AppBundle\Entity\InvitationRequest $invitation
     *
     * @return Project
     */
    public function addInvitation(\AppBundle\Entity\InvitationRequest $invitation)
    {
        $this->invitations[] = $invitation;

        return $this;
    }

    /**
     * Remove invitationRequests
     *
     * @param \AppBundle\Entity\InvitationRequest $invitation
     */
    public function removeInvitationRequests(\AppBundle\Entity\InvitationRequest $invitation)
    {
        $this->invitations->removeElement($invitation);
    }

    /**

    /**
     * Add projectUser
     *
     * @param \AppBundle\Entity\ProjectUser $projectUser
     *
     * @return Project
     */
    public function addProjectUser(\AppBundle\Entity\ProjectUser $projectUser)
    {
        $this->projectUsers[] = $projectUser;

        return $this;
    }

    /**
     * Remove projectUser
     *
     * @param \AppBundle\Entity\ProjectUser $projectUser
     */
    public function removeProjectUser(\AppBundle\Entity\ProjectUser $projectUser)
    {
        $this->projectUsers->removeElement($projectUser);
    }

    /**
     * Get projectUsers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProjectUsers()
    {
        return $this->projectUsers;
    }
}
