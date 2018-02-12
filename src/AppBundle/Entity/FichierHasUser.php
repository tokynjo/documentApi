<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FichierHasUser
 *
 * @ORM\Table(name="my_fichier_has_user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FichierHasUserRepository")
 */
class FichierHasUser
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
     * @ORM\ManyToOne(targetEntity="Fichiers", inversedBy="fichierHasUsers", cascade={"persist"})
     * @ORM\JoinColumn(name="id_fichier", referencedColumnName="id")
     */
    private $fichier;

    /**
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User", inversedBy="fichierHasUsers", cascade={"persist"})
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     */
    private $user;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="fichierHasUsers", cascade={"persist"})
     * @ORM\JoinColumn(name="id_role", referencedColumnName="id")
     */
    private $role;

    /**
     * @var int
     *
     * @ORM\Column(name="synchro", type="integer", nullable=false)
     */
    private $synchro;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime", nullable=false)
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return FichierHasUser
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
     * @return FichierHasUser
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
     * @return FichierHasUser
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
     * Set fichier
     *
     * @param \AppBundle\Entity\Fichiers $fichier
     *
     * @return FichierHasUser
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
     * Set user
     *
     * @param \ApiBundle\Entity\User $user
     *
     * @return FichierHasUser
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
     * @return FichierHasUser
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
     * Set role
     *
     * @param \AppBundle\Entity\Role $role
     *
     * @return FichierHasUser
     */
    public function setRole(\AppBundle\Entity\Role $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \AppBundle\Entity\Role
     */
    public function getRole()
    {
        return $this->role;
    }
}
