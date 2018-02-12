<?php

namespace ApiBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="utilisateur")
 * @UniqueEntity("email")
 * @UniqueEntity("username")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Client", inversedBy="users", cascade={"persist"})
     * @ORM\JoinColumn(name="id_client", referencedColumnName="id")
     */
    private $client;

    /**
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="createdBy", cascade={"persist"})
     * @ORM\JoinColumn(name="id_creator", referencedColumnName="id")
     */
    private $creator;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="creator", cascade={"persist"})
     */
    private $createdBy;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="text" )
     */
    private $hash;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string" )
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string" )
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string",nullable=true )
     */
    private $telephone;

    /**
     * @var integer
     *
     * @ORM\Column(name="indicatif", type="integer" ,nullable=true)
     */
    private $indicatif;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string" ,nullable=true)
     */
    private $mobile;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string" ,nullable=true)
     */
    private $titre;

    /**
     * @var integer
     * @ORM\Column(columnDefinition="TINYINT DEFAULT 0 NOT NULL")
     * @ORM\Column(name="del", type="integer" )
     */
    private $del;

    /**
     * @var integer
     * @ORM\Column(columnDefinition="TINYINT DEFAULT 0 NOT NULL")
     * @ORM\Column(name="externe", type="integer" )
     */
    private $externe;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_credit", type="integer" )
     */
    private $nbCredit;

    /**
     * @var integer
     * @ORM\Column(columnDefinition="TINYINT DEFAULT 0 NOT NULL")
     * @ORM\Column(name="mailing_actu", type="integer", nullable=true )
     */
    private $mailingActu;

    /**
     * @var integer
     * @ORM\Column(columnDefinition="TINYINT DEFAULT 0 NOT NULL")
     * @ORM\Column(name="mailing_promo", type="integer", nullable=true )
     */
    private $mailingPromo;

    /**
     * @var integer
     * @ORM\Column(columnDefinition="TINYINT DEFAULT 0 NOT NULL")
     * @ORM\Column(name="mailing_neobe", type="integer", nullable=true)
     */
    private $mailingNeobe;

    /**
     * @var integer
     * @ORM\Column(columnDefinition="TINYINT DEFAULT 0 NOT NULL")
     * @ORM\Column(name="statut", type="integer", nullable=true)
     */
    private $statut;

    /**
     * @var integer
     * @ORM\Column(name="id_bu", type="integer", nullable=true)
     */
    private $idBu;

    /**
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TypeI18n", inversedBy="users", cascade={"persist"})
     * @ORM\JoinColumn(name="id_langue", referencedColumnName="id")
     */
    private $langue;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="first_login", type="datetime", nullable=true)
     */
    private $firstLogin;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", nullable=true)
     */
    private $avatar;
    /**
     * @var string
     *
     * @ORM\Column(name="origine", type="string", nullable=true)
     */
    private $origine;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Dossiers", mappedBy="createdBy", cascade={"persist"})
     */
    private $dossiersCreated;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Dossiers", mappedBy="deletedBy", cascade={"persist"})
     */
    private $dossiersDeleted;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Fichiers", mappedBy="deletedBy", cascade={"persist"})
     */
    private $fichiersDeleted;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Fichiers", mappedBy="user", cascade={"persist"})
     */
    private $fichiersUser;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Project", mappedBy="user", cascade={"persist"})
     */
    private $projectsUser;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\FichierHasUser", mappedBy="user", cascade={"persist"})
     */
    private $fichierHasUsers;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\DossierHasUser", mappedBy="user", cascade={"persist"})
     */
    private $dossierHasUsers;


    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Add dossiersCreated
     *
     * @param \AppBundle\Entity\Dossiers $dossiersCreated
     *
     * @return User
     */
    public function addDossiersCreated(\AppBundle\Entity\Dossiers $dossiersCreated)
    {
        $this->dossiersCreated[] = $dossiersCreated;

        return $this;
    }

    /**
     * Remove dossiersCreated
     *
     * @param \AppBundle\Entity\Dossiers $dossiersCreated
     */
    public function removeDossiersCreated(\AppBundle\Entity\Dossiers $dossiersCreated)
    {
        $this->dossiersCreated->removeElement($dossiersCreated);
    }

    /**
     * Get dossiersCreated
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDossiersCreated()
    {
        return $this->dossiersCreated;
    }

    /**
     * Add dossiersDeleted
     *
     * @param \AppBundle\Entity\Dossiers $dossiersDeleted
     *
     * @return User
     */
    public function addDossiersDeleted(\AppBundle\Entity\Dossiers $dossiersDeleted)
    {
        $this->dossiersDeleted[] = $dossiersDeleted;

        return $this;
    }

    /**
     * Remove dossiersDeleted
     *
     * @param \AppBundle\Entity\Dossiers $dossiersDeleted
     */
    public function removeDossiersDeleted(\AppBundle\Entity\Dossiers $dossiersDeleted)
    {
        $this->dossiersDeleted->removeElement($dossiersDeleted);
    }

    /**
     * Get dossiersDeleted
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDossiersDeleted()
    {
        return $this->dossiersDeleted;
    }

    /**
     * Add fichiersDeleted
     *
     * @param \AppBundle\Entity\Fichiers $fichiersDeleted
     *
     * @return User
     */
    public function addFichiersDeleted(\AppBundle\Entity\Fichiers $fichiersDeleted)
    {
        $this->fichiersDeleted[] = $fichiersDeleted;

        return $this;
    }

    /**
     * Remove fichiersDeleted
     *
     * @param \AppBundle\Entity\Fichiers $fichiersDeleted
     */
    public function removeFichiersDeleted(\AppBundle\Entity\Fichiers $fichiersDeleted)
    {
        $this->fichiersDeleted->removeElement($fichiersDeleted);
    }

    /**
     * Get fichiersDeleted
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFichiersDeleted()
    {
        return $this->fichiersDeleted;
    }

    /**
     * Add fichiersUser
     *
     * @param \AppBundle\Entity\Fichiers $fichiersUser
     *
     * @return User
     */
    public function addFichiersUser(\AppBundle\Entity\Fichiers $fichiersUser)
    {
        $this->fichiersUser[] = $fichiersUser;

        return $this;
    }

    /**
     * Remove fichiersUser
     *
     * @param \AppBundle\Entity\Fichiers $fichiersUser
     */
    public function removeFichiersUser(\AppBundle\Entity\Fichiers $fichiersUser)
    {
        $this->fichiersUser->removeElement($fichiersUser);
    }

    /**
     * Get fichiersUser
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFichiersUser()
    {
        return $this->fichiersUser;
    }

    /**
     * Add projectsUser
     *
     * @param \AppBundle\Entity\Project $projectsUser
     *
     * @return User
     */
    public function addProjectsUser(\AppBundle\Entity\Project $projectsUser)
    {
        $this->projectsUser[] = $projectsUser;

        return $this;
    }

    /**
     * Remove projectsUser
     *
     * @param \AppBundle\Entity\Project $projectsUser
     */
    public function removeProjectsUser(\AppBundle\Entity\Project $projectsUser)
    {
        $this->projectsUser->removeElement($projectsUser);
    }

    /**
     * Get projectsUser
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProjectsUser()
    {
        return $this->projectsUser;
    }

    /**
     * Add fichierHasUser
     *
     * @param \AppBundle\Entity\FichierHasUser $fichierHasUser
     *
     * @return User
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

    /**
     * Set hash
     *
     * @param string $hash
     *
     * @return User
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
     * Set nom
     *
     * @param string $nom
     *
     * @return User
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return User
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set indicatif
     *
     * @param integer $indicatif
     *
     * @return User
     */
    public function setIndicatif($indicatif)
    {
        $this->indicatif = $indicatif;

        return $this;
    }

    /**
     * Get indicatif
     *
     * @return integer
     */
    public function getIndicatif()
    {
        return $this->indicatif;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     *
     * @return User
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return User
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set del
     *
     * @param string $del
     *
     * @return User
     */
    public function setDel($del)
    {
        $this->del = $del;

        return $this;
    }

    /**
     * Get del
     *
     * @return string
     */
    public function getDel()
    {
        return $this->del;
    }

    /**
     * Set externe
     *
     * @param string $externe
     *
     * @return User
     */
    public function setExterne($externe)
    {
        $this->externe = $externe;

        return $this;
    }

    /**
     * Get externe
     *
     * @return string
     */
    public function getExterne()
    {
        return $this->externe;
    }

    /**
     * Set nbCredit
     *
     * @param integer $nbCredit
     *
     * @return User
     */
    public function setNbCredit($nbCredit)
    {
        $this->nbCredit = $nbCredit;

        return $this;
    }

    /**
     * Get nbCredit
     *
     * @return integer
     */
    public function getNbCredit()
    {
        return $this->nbCredit;
    }

    /**
     * Set mailingActu
     *
     * @param string $mailingActu
     *
     * @return User
     */
    public function setMailingActu($mailingActu)
    {
        $this->mailingActu = $mailingActu;

        return $this;
    }

    /**
     * Get mailingActu
     *
     * @return string
     */
    public function getMailingActu()
    {
        return $this->mailingActu;
    }

    /**
     * Set mailingPromo
     *
     * @param string $mailingPromo
     *
     * @return User
     */
    public function setMailingPromo($mailingPromo)
    {
        $this->mailingPromo = $mailingPromo;

        return $this;
    }

    /**
     * Get mailingPromo
     *
     * @return string
     */
    public function getMailingPromo()
    {
        return $this->mailingPromo;
    }

    /**
     * Set mailingNeobe
     *
     * @param string $mailingNeobe
     *
     * @return User
     */
    public function setMailingNeobe($mailingNeobe)
    {
        $this->mailingNeobe = $mailingNeobe;

        return $this;
    }

    /**
     * Get mailingNeobe
     *
     * @return string
     */
    public function getMailingNeobe()
    {
        return $this->mailingNeobe;
    }

    /**
     * Set statut
     *
     * @param string $statut
     *
     * @return User
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return string
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set idBu
     *
     * @param integer $idBu
     *
     * @return User
     */
    public function setIdBu($idBu)
    {
        $this->idBu = $idBu;

        return $this;
    }

    /**
     * Get idBu
     *
     * @return integer
     */
    public function getIdBu()
    {
        return $this->idBu;
    }

    /**
     * Set firstLogin
     *
     * @param \DateTime $firstLogin
     *
     * @return User
     */
    public function setFirstLogin($firstLogin)
    {
        $this->firstLogin = $firstLogin;

        return $this;
    }

    /**
     * Get firstLogin
     *
     * @return \DateTime
     */
    public function getFirstLogin()
    {
        return $this->firstLogin;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     *
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set origine
     *
     * @param string $origine
     *
     * @return User
     */
    public function setOrigine($origine)
    {
        $this->origine = $origine;

        return $this;
    }

    /**
     * Get origine
     *
     * @return string
     */
    public function getOrigine()
    {
        return $this->origine;
    }

    /**
     * Set client
     *
     * @param \AppBundle\Entity\Client $client
     *
     * @return User
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
     * Set creator
     *
     * @param \ApiBundle\Entity\User $creator
     *
     * @return User
     */
    public function setCreator(\ApiBundle\Entity\User $creator = null)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return \ApiBundle\Entity\User
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Add createdBy
     *
     * @param \ApiBundle\Entity\User $createdBy
     *
     * @return User
     */
    public function addCreatedBy(\ApiBundle\Entity\User $createdBy)
    {
        $this->createdBy[] = $createdBy;

        return $this;
    }

    /**
     * Remove createdBy
     *
     * @param \ApiBundle\Entity\User $createdBy
     */
    public function removeCreatedBy(\ApiBundle\Entity\User $createdBy)
    {
        $this->createdBy->removeElement($createdBy);
    }

    /**
     * Get createdBy
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set langue
     *
     * @param \AppBundle\Entity\TypeI18n $langue
     *
     * @return User
     */
    public function setLangue(\AppBundle\Entity\TypeI18n $langue = null)
    {
        $this->langue = $langue;

        return $this;
    }

    /**
     * Get langue
     *
     * @return \AppBundle\Entity\TypeI18n
     */
    public function getLangue()
    {
        return $this->langue;
    }

    /**
     * Add dossierHasUser
     *
     * @param \AppBundle\Entity\DossierHasUser $dossierHasUser
     *
     * @return User
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
}
