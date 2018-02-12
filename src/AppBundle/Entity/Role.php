<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Role
 *
 * @ORM\Table(name="my_role")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RoleRepository")
 */
class Role
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
     * @ORM\OneToMany(targetEntity="DossierHasUser", mappedBy="role", cascade={"persist"})
     */
    private $dossierHasUsers;

    /**
     * @ORM\OneToMany(targetEntity="FichierHasUser", mappedBy="role", cascade={"persist"})
     */
    private $fichierHasUsers;

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
     * Constructor
     */
    public function __construct()
    {
        $this->dossierHasUsers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fichierHasUsers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add dossierHasUser
     *
     * @param \AppBundle\Entity\DossierHasUser $dossierHasUser
     *
     * @return Role
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
     * Add fichierHasUser
     *
     * @param \AppBundle\Entity\FichierHasUser $fichierHasUser
     *
     * @return Role
     */
    public function addFichierHasUser(\AppBundle\Entity\FichierHasUser $fichierHasUser)
    {
        $this->fichierHasUsers[] = $fichierHasUser;

        return $this;
    }

    /**
     * Remove fichierHasUser
     *
     * @param \AppBundle\Entity\FichierHasUser $fichierHasUser
     */
    public function removeFichierHasUser(\AppBundle\Entity\FichierHasUser $fichierHasUser)
    {
        $this->fichierHasUsers->removeElement($fichierHasUser);
    }

    /**
     * Get fichierHasUsers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFichierHasUsers()
    {
        return $this->fichierHasUsers;
    }
}
