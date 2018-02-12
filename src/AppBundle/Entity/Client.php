<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Client
 *
 * @ORM\Table(name="my_client")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClientRepository")
 */
class Client
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
     * @var integer
     *
     * @ORM\Column(name="id_categorie", type="integer", length=11)
     */
    private $categorie;

    /**
     * @var string
     *
     * @ORM\Column(name="societe", type="string", length=255)
     */
    private $societe;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255, nullable=true)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="email2", type="string", length=255)
     */
    private $email2;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;


    /**
     * @var string
     *
     * @ORM\Column(name="adressebis", type="string", length=255)
     */
    private $adresseBis;

    /**
     * @var string
     *
     * @ORM\Column(name="cp", type="string", length=255)
     */
    private $cp;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255)
     */
    private $ville;

    /**
     *
     * @ORM\ManyToOne(targetEntity="ClientPays", inversedBy="clients", cascade={"persist"})
     * @ORM\JoinColumn(name="id_pays", referencedColumnName="id")
     */
    private $clientPays;


    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=255)
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone2", type="string", length=255)
     */
    private $telephone2;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=255)
     */
    private $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=255)
     */
    private $mobile;


    /**
     * @var string
     *
     * @ORM\Column(name="siret", type="string", length=255)
     */
    private $siret;

    /**
     * @var string
     *
     * @ORM\Column(name="ape", type="string", length=255)
     */
    private $ape;

    /**
     * @var string
     *
     * @ORM\Column(name="tva", type="string", length=255)
     */
    private $tva;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="actif", type="string", length=1)
     */
    private $actif;

    /**
     * @var string
     *
     * @ORM\Column(name="id_nas", type="string", length=255)
     */
    private $idNas;

    /**
     * @var string
     *
     * @ORM\Column(name="expiration", type="datetime" )
     */
    private $expiration;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_offre", type="integer", length=11)
     */
    private $offre;

    /**
     * @var string
     *
     * @ORM\Column(name="cle_api", type="string", length=255,nullable=true)
     */
    private $cleApi;

    /**
     * @var string
     *
     * @ORM\Column(name="login_api", type="string", length=255, nullable=true)
     */
    private $loginApi;

    /**
     * @var string
     *
     * @ORM\Column(name="password_api", type="string", length=255, nullable=true)
     */
    private $passwordApi;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_declination", type="integer", length=11, nullable=true)
     */
    private $declination;

    /**
     * @var string
     *
     * @ORM\Column(name="created_at", type="datetime" )
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="demo", type="integer" )
     */
    private $demo;

    /**
     *
     * @ORM\ManyToOne(targetEntity="ClientType", inversedBy="clients", cascade={"persist"})
     * @ORM\JoinColumn(name="id_type", referencedColumnName="id")
     */
    private $clientType;

    /**
     * @ORM\OneToMany(targetEntity="Project", mappedBy="client", cascade={"persist"})
     */
    private $projects;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\User", mappedBy="client", cascade={"persist"})
     */
    private $users;
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
     * Set societe
     *
     * @param string $societe
     *
     * @return Client
     */
    public function setSociete($societe)
    {
        $this->societe = $societe;

        return $this;
    }

    /**
     * Get societe
     *
     * @return string
     */
    public function getSociete()
    {
        return $this->societe;
    }

    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return Client
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Client
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
     * @return Client
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
     * Set email
     *
     * @param string $email
     *
     * @return Client
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email2
     *
     * @param string $email2
     *
     * @return Client
     */
    public function setEmail2($email2)
    {
        $this->email2 = $email2;

        return $this;
    }

    /**
     * Get email2
     *
     * @return string
     */
    public function getEmail2()
    {
        return $this->email2;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Client
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set categorie
     *
     * @param integer $categorie
     *
     * @return Client
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return integer
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set adresseBis
     *
     * @param string $adresseBis
     *
     * @return Client
     */
    public function setAdresseBis($adresseBis)
    {
        $this->adresseBis = $adresseBis;

        return $this;
    }

    /**
     * Get adresseBis
     *
     * @return string
     */
    public function getAdresseBis()
    {
        return $this->adresseBis;
    }

    /**
     * Set cp
     *
     * @param string $cp
     *
     * @return Client
     */
    public function setCp($cp)
    {
        $this->cp = $cp;

        return $this;
    }

    /**
     * Get cp
     *
     * @return string
     */
    public function getCp()
    {
        return $this->cp;
    }

    /**
     * Set ville
     *
     * @param string $ville
     *
     * @return Client
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return Client
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
     * Set telephone2
     *
     * @param string $telephone2
     *
     * @return Client
     */
    public function setTelephone2($telephone2)
    {
        $this->telephone2 = $telephone2;

        return $this;
    }

    /**
     * Get telephone2
     *
     * @return string
     */
    public function getTelephone2()
    {
        return $this->telephone2;
    }

    /**
     * Set fax
     *
     * @param string $fax
     *
     * @return Client
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     *
     * @return Client
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
     * Set siret
     *
     * @param string $siret
     *
     * @return Client
     */
    public function setSiret($siret)
    {
        $this->siret = $siret;

        return $this;
    }

    /**
     * Get siret
     *
     * @return string
     */
    public function getSiret()
    {
        return $this->siret;
    }

    /**
     * Set ape
     *
     * @param string $ape
     *
     * @return Client
     */
    public function setApe($ape)
    {
        $this->ape = $ape;

        return $this;
    }

    /**
     * Get ape
     *
     * @return string
     */
    public function getApe()
    {
        return $this->ape;
    }

    /**
     * Set tva
     *
     * @param string $tva
     *
     * @return Client
     */
    public function setTva($tva)
    {
        $this->tva = $tva;

        return $this;
    }

    /**
     * Get tva
     *
     * @return string
     */
    public function getTva()
    {
        return $this->tva;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Client
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set actif
     *
     * @param string $actif
     *
     * @return Client
     */
    public function setActif($actif)
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * Get actif
     *
     * @return string
     */
    public function getActif()
    {
        return $this->actif;
    }

    /**
     * Set idNas
     *
     * @param string $idNas
     *
     * @return Client
     */
    public function setIdNas($idNas)
    {
        $this->idNas = $idNas;

        return $this;
    }

    /**
     * Get idNas
     *
     * @return string
     */
    public function getIdNas()
    {
        return $this->idNas;
    }

    /**
     * Set expiration
     *
     * @param \DateTime $expiration
     *
     * @return Client
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
     * Set offre
     *
     * @param integer $offre
     *
     * @return Client
     */
    public function setOffre($offre)
    {
        $this->offre = $offre;

        return $this;
    }

    /**
     * Get offre
     *
     * @return integer
     */
    public function getOffre()
    {
        return $this->offre;
    }

    /**
     * Set cleApi
     *
     * @param string $cleApi
     *
     * @return Client
     */
    public function setCleApi($cleApi)
    {
        $this->cleApi = $cleApi;

        return $this;
    }

    /**
     * Get cleApi
     *
     * @return string
     */
    public function getCleApi()
    {
        return $this->cleApi;
    }

    /**
     * Set loginApi
     *
     * @param string $loginApi
     *
     * @return Client
     */
    public function setLoginApi($loginApi)
    {
        $this->loginApi = $loginApi;

        return $this;
    }

    /**
     * Get loginApi
     *
     * @return string
     */
    public function getLoginApi()
    {
        return $this->loginApi;
    }

    /**
     * Set passwordApi
     *
     * @param string $passwordApi
     *
     * @return Client
     */
    public function setPasswordApi($passwordApi)
    {
        $this->passwordApi = $passwordApi;

        return $this;
    }

    /**
     * Get passwordApi
     *
     * @return string
     */
    public function getPasswordApi()
    {
        return $this->passwordApi;
    }

    /**
     * Set declination
     *
     * @param integer $declination
     *
     * @return Client
     */
    public function setDeclination($declination)
    {
        $this->declination = $declination;

        return $this;
    }

    /**
     * Get declination
     *
     * @return integer
     */
    public function getDeclination()
    {
        return $this->declination;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Client
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
     * Set demo
     *
     * @param integer $demo
     *
     * @return Client
     */
    public function setDemo($demo)
    {
        $this->demo = $demo;

        return $this;
    }

    /**
     * Get demo
     *
     * @return integer
     */
    public function getDemo()
    {
        return $this->demo;
    }

    /**
     * Set clientType
     *
     * @param \AppBundle\Entity\ClientType $clientType
     *
     * @return Client
     */
    public function setClientType(\AppBundle\Entity\ClientType $clientType = null)
    {
        $this->clientType = $clientType;

        return $this;
    }

    /**
     * Get clientType
     *
     * @return \AppBundle\Entity\ClientType
     */
    public function getClientType()
    {
        return $this->clientType;
    }

    /**
     * Set clientPays
     *
     * @param \AppBundle\Entity\ClientPays $clientPays
     *
     * @return Client
     */
    public function setClientPays(\AppBundle\Entity\ClientPays $clientPays = null)
    {
        $this->clientPays = $clientPays;

        return $this;
    }

    /**
     * Get clientPays
     *
     * @return \AppBundle\Entity\ClientPays
     */
    public function getClientPays()
    {
        return $this->clientPays;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->projects = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add project
     *
     * @param \AppBundle\Entity\Project $project
     *
     * @return Client
     */
    public function addProject(\AppBundle\Entity\Project $project)
    {
        $this->projects[] = $project;

        return $this;
    }

    /**
     * Remove project
     *
     * @param \AppBundle\Entity\Project $project
     */
    public function removeProject(\AppBundle\Entity\Project $project)
    {
        $this->projects->removeElement($project);
    }

    /**
     * Get projects
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * Add user
     *
     * @param \ApiBundle\Entity\User $user
     *
     * @return Client
     */
    public function addUser(\ApiBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \ApiBundle\Entity\User $user
     */
    public function removeUser(\ApiBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }
}
