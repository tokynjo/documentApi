<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class FolderUser : ORM entity for the tablemy_dossier_has_user
 *
 * @ORM\Table(name="my_dossier_has_user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FolderUserRepository")
 */
class FolderUser
{

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User", inversedBy="dossierHasUsers", cascade={"persist"})
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Folder", inversedBy="folderUsers", cascade={"persist"})
     * @ORM\JoinColumn(name="id_dossier", referencedColumnName="id")
     */
    private $folder;

    /**
     * @var int
     *
     * @ORM\Column(name="id_right", type="integer", nullable=false)
     */
    private $rightId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expired_at", type="datetime", nullable=false)
     */
    private $expiredAt;


    /**
     * @var int
     *
     * @ORM\Column(name="synchro", type="integer", nullable=false)
     */
    private $synchro;



    /**
     * @var int
     *
     * @ORM\Column(name="order_by_position", type="integer", nullable=true)
     */
    private $orderByPosition;

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
     * Set synchro
     *
     * @param integer $synchro
     *
     * @return DossierHasUser
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return DossierHasUser
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
     * Set expiredAt
     *
     * @param \DateTime $expiredAt
     *
     * @return DossierHasUser
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
     * @return DossierHasUser
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
     * Set dossier
     *
     * @param \AppBundle\Entity\Folder $folder
     *
     * @return $this
     */
    public function setFolder(Folder $folder = null)
    {
        $this->folder = $folder;

        return $this;
    }

    /**
     * Get dossier
     *
     * @return \AppBundle\Entity\Folder
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * Set user
     *
     * @param \ApiBundle\Entity\User $user
     *
     * @return DossierHasUser
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
     * Set rightId
     *
     * @param integer $rightId
     *
     * @return FolderUser
     */
    public function setRightId($rightId)
    {
        $this->rightId = $rightId;

        return $this;
    }

    /**
     * Get rightId
     *
     * @return integer
     */
    public function getRightId()
    {
        return $this->rightId;
    }
}
