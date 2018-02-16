<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ProjectUser, orm entity for the table my_project_has_user
 *
 * @ORM\Table(name="my_project_has_user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectUserRepository")
 */
class ProjectUser
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
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="projectUser")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     * */
    private $project;
    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User", inversedBy="projectUser")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * */
    private $user;
    /**
     * @var \DateTime
     * @ORM\Column(name="updated_at" ,type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(name="expired_at" ,type="datetime")
     */
    private $expiredAt;

    /**
     * @var integer
     * @ORM\Column(name="order_by_position", type="integer", length=4)
     */
    private $orderByPosition;

    /**
     * Constructor
     * @return ProjectUser
     */
    function __construct()
    {
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExpiredAt()
    {
        return $this->expiredAt;
    }

    /**
     * @param \DateTime $expiredAt
     * @return $this
     */
    public function setExpiredAt($expiredAt)
    {
        $this->expiredAt = $expiredAt;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return int
     */
    public function getOrderByPosition()
    {
        return $this->orderByPosition;
    }

    /**
     * @param int $orderByPosition
     * @return $this
     */
    public function setOrderByPosition($orderByPosition)
    {
        $this->orderByPosition = $orderByPosition;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param mixed $project
     * @return $this
     */
    public function setProject($project)
    {
        $this->project = $project;
        return $this ;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }
}
