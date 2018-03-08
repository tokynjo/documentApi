<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * class Client
 * Entity for the client off the application
 * @package AppBundle\Entity
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
     *
     * @ORM\ManyToOne(targetEntity="ClientType", inversedBy="clients", cascade={"persist"})
     * @ORM\JoinColumn(name="id_client_type", referencedColumnName="id")
     */
    private $type;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="clients", cascade={"persist"})
     * @ORM\JoinColumn(name="id_category", referencedColumnName="id")
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="societe", type="string", length=255)
     */
    private $society;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255, nullable=true)
     */
    private $civility;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $firstName;
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
    private $address;


    /**
     * @var string
     *
     * @ORM\Column(name="adressebis", type="string", length=255)
     */
    private $addressBis;
    /**
     * @var string
     *
     * @ORM\Column(name="cp", type="string", length=255)
     */
    private $zipCode;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=255)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone2", type="string", length=255)
     */
    private $phone2;

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
    private $vat;

    /**
     * @var string
     *
     * @ORM\Column(name="emailing_neobe", type="string", length=1)
     */
    private $emailingNeobe;

    /**
     * @var string
     *
     * @ORM\Column(name="emailing_partenaire", type="string", length=1)
     */
    private $emailingPartner;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_fai", type="integer", length=11)
     */
    private $idFai;

    /**
     * @var \Datetime
     *
     * @ORM\Column(name="date_inscription", type="datetime")
     */
    private $subscriptionDate;

    /**
     * @var string
     *
     * @ORM\Column(name="actif", type="string", length=1)
     */
    private $active;


    /**
     * @var integer
     *
     * @ORM\Column(name="id_devise", type="integer", length=11)
     */
    private $devise;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_inflation", type="integer", length=11)
     */
    private $inflation;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Country", inversedBy="clients", cascade={"persist"})
     * @ORM\JoinColumn(name="id_pays", referencedColumnName="id")
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="id_commercial", type="string", length=255)
     */
    private $commercial;

    /**
     * @var string
     *
     * @ORM\Column(name="emailing_mail_sec", type="string", length=1)
     */
    private $emailingMailSec;

    /**
     * @var string
     *
     * @ORM\Column(name="prelevement_annuel", type="string", length=2)
     */
    private $prelevementAnnuel;

    /**
     * @var string
     *
     * @ORM\Column(name="origine", type="string", length=255)
     */
    private $origin;

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
    private $keyApi;

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
     * @var \datetime
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
     * @ORM\OneToMany(targetEntity="Project", mappedBy="client", cascade={"persist"})
     */
    private $projects;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\User", mappedBy="client", cascade={"persist"})
     */
    private $users;

    /**
     *
     * @ORM\OneToMany(targetEntity="FolderLog", mappedBy="client", cascade={"persist"})
     */
    private $folderLogs;

    /**
     *
     * @ORM\OneToMany(targetEntity="FileLog", mappedBy="client", cascade={"persist"})
     */
    private $fileLogs;

    /**
     *
     * @ORM\OneToMany(targetEntity="Log", mappedBy="client", cascade={"persist"})
     */
    private $logs;

/**
     * Constructor
     */
    public function __construct()
    {
        $this->projects = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return string
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param string $active
     * @return $this;
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return $this;
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddressBis()
    {
        return $this->addressBis;
    }

    /**
     * @param string $addressBis
     * @return $this;
     */
    public function setAddressBis($addressBis)
    {
        $this->addressBis = $addressBis;
        return $this;
    }

    /**
     * @return string
     */
    public function getApe()
    {
        return $this->ape;
    }

    /**
     * @param string $ape
     * @return $this;
     */
    public function setApe($ape)
    {
        $this->ape = $ape;
        return $this;
    }

/**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     * @return $this
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getCivility()
    {
        return $this->civility;
    }

    /**
     * @param string $civility
     * @return $this
     */
    public function setCivility($civility)
    {
        $this->civility = $civility;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClientType()
    {
        return $this->clientType;
    }

    /**
     * @param mixed $clientType
     * @return $this
     */
    public function setClientType($clientType)
    {
        $this->clientType = $clientType;
        return $this;
    }

    /**
     * @return string
     */
    public function getCommercial()
    {
        return $this->commercial;
    }

    /**
     * @param string $commercial
     * @return $this
     */
    public function setCommercial($commercial)
    {
        $this->commercial = $commercial;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return \datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \datetime $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return int
     */
    public function getDeclination()
    {
        return $this->declination;
    }

    /**
     * @param int $declination
     * @return $this
     */
    public function setDeclination($declination)
    {
        $this->declination = $declination;
        return $this;
    }

    /**
     * @return string
     */
    public function getDemo()
    {
        return $this->demo;
    }

    /**
     * @param string $demo
     * @return $this
     */
    public function setDemo($demo)
    {
        $this->demo = $demo;
        return $this;
    }

    /**
     * @return int
     */
    public function getDevise()
    {
        return $this->devise;
    }

    /**
     * @param int $devise
     */
    public function setDevise($devise)
    {
        $this->devise = $devise;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail2()
    {
        return $this->email2;
    }

    /**
     * @param string $email2
     */
    public function setEmail2($email2)
    {
        $this->email2 = $email2;
    }

    /**
     * @return string
     */
    public function getEmailingMailSec()
    {
        return $this->emailingMailSec;
    }

    /**
     * @param string $emailingMailSec
     */
    public function setEmailingMailSec($emailingMailSec)
    {
        $this->emailingMailSec = $emailingMailSec;
    }

    /**
     * @return string
     */
    public function getEmailingNeobe()
    {
        return $this->emailingNeobe;
    }

    /**
     * @param string $emailingNeobe
     */
    public function setEmailingNeobe($emailingNeobe)
    {
        $this->emailingNeobe = $emailingNeobe;
    }

    /**
     * @return string
     */
    public function getEmailingPartner()
    {
        return $this->emailingPartner;
    }

    /**
     * @param string $emailingPartner
     */
    public function setEmailingPartner($emailingPartner)
    {
        $this->emailingPartner = $emailingPartner;
    }

    /**
     * @return string
     */
    public function getExpiration()
    {
        return $this->expiration;
    }

    /**
     * @param string $expiration
     */
    public function setExpiration($expiration)
    {
        $this->expiration = $expiration;
    }

    /**
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param string $fax
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getIdFai()
    {
        return $this->idFai;
    }

    /**
     * @param int $idFai
     */
    public function setIdFai($idFai)
    {
        $this->idFai = $idFai;
    }

    /**
     * @return string
     */
    public function getIdNas()
    {
        return $this->idNas;
    }

    /**
     * @param string $idNas
     */
    public function setIdNas($idNas)
    {
        $this->idNas = $idNas;
    }

/**
     * @return int
     */
    public function getInflation()
    {
        return $this->inflation;
    }

    /**
     * @param int $inflation
     */
    public function setInflation($inflation)
    {
        $this->inflation = $inflation;
    }

    /**
     * @return string
     */
    public function getKeyApi()
    {
        return $this->keyApi;
    }

    /**
     * @param string $keyApi
     */
    public function setKeyApi($keyApi)
    {
        $this->keyApi = $keyApi;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getLoginApi()
    {
        return $this->loginApi;
    }

    /**
     * @param string $loginApi
     */
    public function setLoginApi($loginApi)
    {
        $this->loginApi = $loginApi;
    }

    /**
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param string $mobile
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }

    /**
     * @return int
     */
    public function getOffre()
    {
        return $this->offre;
    }

    /**
     * @param int $offre
     */
    public function setOffre($offre)
    {
        $this->offre = $offre;
    }

    /**
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @param string $origin
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;
    }

/**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPasswordApi()
    {
        return $this->passwordApi;
    }

    /**
     * @param string $passwordApi
     */
    public function setPasswordApi($passwordApi)
    {
        $this->passwordApi = $passwordApi;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getPhone2()
    {
        return $this->phone2;
    }

    /**
     * @param string $phone2
     */
    public function setPhone2($phone2)
    {
        $this->phone2 = $phone2;
    }

    /**
     * @return string
     */
    public function getPrelevementAnnuel()
    {
        return $this->prelevementAnnuel;
    }

    /**
     * @param string $prelevementAnnuel
     */
    public function setPrelevementAnnuel($prelevementAnnuel)
    {
        $this->prelevementAnnuel = $prelevementAnnuel;
    }

/**
     * @return string
     */
    public function getSiret()
    {
        return $this->siret;
    }

    /**
     * @param string $siret
     */
    public function setSiret($siret)
    {
        $this->siret = $siret;
    }

    /**
     * @return string
     */
    public function getSociety()
    {
        return $this->society;
    }

    /**
     * @param string $society
     */
    public function setSociety($society)
    {
        $this->society = $society;
    }

/**
     * @return \Datetime
     */
    public function getSubscriptionDate()
    {
        return $this->subscriptionDate;
    }

    /**
     * @param \Datetime $subscriptionDate
     */
    public function setSubscriptionDate($subscriptionDate)
    {
        $this->subscriptionDate = $subscriptionDate;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * @param string $vat
     */
    public function setVat($vat)
    {
        $this->vat = $vat;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param string $zipCode
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
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
     * @param Project $project
     */
    public function removeProject(Project $project)
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

    /**
     * Add folderLog
     *
     * @param \AppBundle\Entity\FolderLog $folderLog
     *
     * @return Client
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
     * Add fileLog
     *
     * @param \AppBundle\Entity\FileLog $fileLog
     *
     * @return Client
     */
    public function addFileLog(\AppBundle\Entity\FileLog $fileLog)
    {
        $this->fileLogs[] = $fileLog;

        return $this;
    }

    /**
     * Remove fileLog
     *
     * @param \AppBundle\Entity\FileLog $fileLog
     */
    public function removeFileLog(\AppBundle\Entity\FileLog $fileLog)
    {
        $this->fileLogs->removeElement($fileLog);
    }

    /**
     * Get fileLogs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFileLogs()
    {
        return $this->fileLogs;
    }
}
