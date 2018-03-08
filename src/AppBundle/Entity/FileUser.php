<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * class FileUser : ORM entity for table my_fichier_has_user
 *
 * @ORM\Table(name="my_fichier_has_user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FileUserRepository")
 */
class FileUser
{


    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User", inversedBy="fichierHasUsers", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="File", inversedBy="fileUsers", cascade={"persist"})
     * @ORM\JoinColumn(name="id_fichier", referencedColumnName="id")
     */
    private $file;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expired_at", type="datetime", nullable=false)
     */
    private $expiredAt;

    /**
     * @var int
     *
     * @ORM\Column(name="order_by_position", type="integer", nullable=true)
     */
    private $orderByPosition;

    /**
     * @var int
     *
     * @ORM\Column(name="synchro", type="integer", nullable=false)
     */
    private $synchro;

    /**
     * @ORM\ManyToOne(targetEntity="Right", inversedBy="fileUser", cascade={"persist"})
     * @ORM\JoinColumn(name="id_right", referencedColumnName="id")
     */
    private $right;

/**
     * Set expiredAt
     *
     * @param \DateTime $expiredAt
     *
     * @return FileUser
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
     * Set orderByPosition
     *
     * @param integer $orderByPosition
     *
     * @return FileUser
     */
    public function setOrderByPosition($orderByPosition)
    {
        $this->orderByPosition = $orderByPosition;

        return $this;
    }

    /**
     * Get orderByPosition
     *
     * @return integer
     */
    public function getOrderByPosition()
    {
        return $this->orderByPosition;
    }

    /**
     * Set file
     *
     * @param \AppBundle\Entity\File $file
     *
     * @return FileUser
     */
    public function setFile(\AppBundle\Entity\File $file = null)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return \AppBundle\Entity\File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set user
     *
     * @param \ApiBundle\Entity\User $user
     *
     * @return FileUser
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
     * Set synchro
     *
     * @param integer $synchro
     *
     * @return FileUser
     */
    public function setSynchro($synchro)
    {
        $this->synchro = $synchro;

        return $this;
    }

    /**
     * Get synchro
     *
     * @return integer
     */
    public function getSynchro()
    {
        return $this->synchro;
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
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return int
     */
    public function getRightId()
    {
        return $this->rightId;
    }

    /**
     * @param int $rightId
     */
    public function setRightId($rightId)
    {
        $this->rightId = $rightId;
    }

    /**
     * Set right
     *
     * @param \AppBundle\Entity\Right $right
     *
     * @return FileUser
     */
    public function setRight(\AppBundle\Entity\Right $right = null)
    {
        $this->right = $right;

        return $this;
    }

    /**
     * Get right
     *
     * @return \AppBundle\Entity\Right
     */
    public function getRight()
    {
        return $this->right;
    }
}
