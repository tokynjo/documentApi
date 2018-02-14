<?php

namespace ApiBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="my_user")
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
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Role", inversedBy="users", cascade={"persist"})
     * @ORM\JoinColumn(name="id_role", referencedColumnName="id")
     */
    private $role;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length="255", nullable=false)
     */
    private $hash;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length="255", nullable=true )
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length="255", nullable=true )
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length="255", nullable=true )
     */
    private $phone;

    /**
     * @var integer
     *
     * @ORM\Column(name="country_phone_code", type="integer" , length="11",nullable=true)
     */
    private $countryPhoneCode;
    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string" ,nullable=true)
     */
    private $mobile;

    /**
     * @var string
     *
     * @ORM\Column(name="civility", type="string" ,nullable=true)
     */
    private $civility;
    /**
     * @var integer
     * @ORM\Column(name="is_deleted", type="integer" , length="4", nullable=true)
     */
    private $isDeleted;

    /**
     * @var integer
     * @ORM\Column(name="is_external", type="integer" , length="4", nullable=true)
     */
    private $isExternal;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_credit", type="integer" , length="4", nullable=false)
     */
    private $nbCredit;

    /**
     * @var integer
     * @ORM\Column(columnDefinition="TINYINT DEFAULT 0")
     * @ORM\Column(name="mailing_actu", type="integer", nullable=true )
     */
    private $mailingActu;

    /**
     * @var integer
     * @ORM\Column(columnDefinition="TINYINT DEFAULT 0")
     * @ORM\Column(name="mailing_promo", type="integer", nullable=true )
     */
    private $mailingPromo;

    /**
     * @var integer
     * @ORM\Column(columnDefinition="TINYINT DEFAULT 0")
     * @ORM\Column(name="mailing_neobe", type="integer", nullable=true)
     */
    private $mailingNeobe;
    /**
     * @var integer
     * @ORM\Column(columnDefinition="TINYINT DEFAULT 0")
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status;
    /**
     * @var integer
     * @ORM\Column(name="id_bu", type="integer", nullable=true)
     */
    private $idBu;

    /**
     * @var integer
     * @ORM\Column(name="id_lang", type="integer", nullable=true)
     */
    private $lang;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="first_login", type="datetime", nullable=true)
     */
    private $firstLogin;

    /**
     * @var string
     *
     * @ORM\Column(name="signup_token", type="string" ,nullable=true)
     */
    private $signUpToken;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", nullable=true)
     */
    private $avatar;
    /**
     * @var string
     *
     * @ORM\Column(name="origin", type="string", nullable=true)
     */
    private $origin;
    /**
     * @var \Datetime
     * @ORM\Column(name="created_at" ,type="datetime")
     */
    private $createdAt;
    /**
     * @var \Datetime
     * @ORM\Column(name="last_login_at" ,type="datetime")
     */
    private $lastLoginAt;
    /**
     * @var string
     * @ORM\Column(name="created_ip" ,type="string", length="100")
     */
    private $createdIp;
    /**
     * @var string
     * @ORM\Column(name="last_ip" ,type="string", length="100")
     */
    private $lastIp;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\News", mappedBy="user", cascade={"persist"})
     */
    private $news;

    /**
     * constructor
     */
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * get avatar
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * set avatar
     * @param string $avatar
     * @return $this
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * get civility
     * @return string
     */
    public function getCivility()
    {
        return $this->civility;
    }

    /**
     * set civility
     * @param string $civility
     * @return $this
     */
    public function setCivility($civility)
    {
        $this->civility = $civility;
        return $this;
    }

    /**
     * get client
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * set client
     * @param mixed $client
     * @return $this
     */
    public function setClient($client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * get country dial code
     * @return int
     */
    public function getCountryPhoneCode()
    {
        return $this->countryPhoneCode;
    }

    /**
     * set country dial code
     * @param int $countryPhoneCode
     * @return $this;
     */
    public function setCountryPhoneCode($countryPhoneCode)
    {
        $this->countryPhoneCode = $countryPhoneCode;
        return $this;
    }

    /**
     * get creation date
     * @return \Datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * set creation date
     * @param \Datetime $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * get ip from the creation
     * @return string
     */
    public function getCreatedIp()
    {
        return $this->createdIp;
    }

    /**
     * set ip from the creation
     * @param string $createdIp
     * @return $this
     */
    public function setCreatedIp($createdIp)
    {
        $this->createdIp = $createdIp;
        return $this;
    }

    /**
     * get user creator
     * @return mixed
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * set user creator
     * @param mixed $creator
     * @return $this
     */
    public function setCreator($creator)
    {
        $this->creator = $creator;
        return $this;
    }

    /**
     * get date of first login
     * @return \DateTime
     */
    public function getFirstLogin()
    {
        return $this->firstLogin;
    }

    /**
     * set date of first login
     * @param \DateTime $firstLogin
     * @return $this
     */
    public function setFirstLogin($firstLogin)
    {
        $this->firstLogin = $firstLogin;
        return $this;
    }

    /**
     * get the first name
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * set the first name (prenom)
     * @param string $firstname
     * @return $this
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * get hash
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * set hash
     * @param string $hash
     * @return $this;
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return int
     */
    public function getIdBu()
    {
        return $this->idBu;
    }

    /**
     * @param int $idBu
     */
    public function setIdBu($idBu)
    {
        $this->idBu = $idBu;
    }

    /**
     * @return int
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * @param int $isDeleted
     * @return $this;
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;
        return $this;
    }

    /**
     * @return int
     */
    public function getIsExternal()
    {
        return $this->isExternal;
    }

    /**
     * @param int $isExternal
     * @return $this;
     */
    public function setIsExternal($isExternal)
    {
        $this->isExternal = $isExternal;
        return $this;
    }

    /**
     * @return int
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param int $lang
     * @return $this
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
        return $this;
    }

    /**
     * get last ip connection
     * @return string
     */
    public function getLastIp()
    {
        return $this->lastIp;
    }

    /**
     * set last ip connection
     * @param string $lastIp
     * @return $this
     */
    public function setLastIp($lastIp)
    {
        $this->lastIp = $lastIp;
        return $this;
    }

    /**
     * @return \Datetime
     */
    public function getLastLoginAt()
    {
        return $this->lastLoginAt;
    }

    /**
     * @param \Datetime $lastLoginAt
     * @return $this
     */
    public function setLastLoginAt($lastLoginAt)
    {
        $this->lastLoginAt = $lastLoginAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * set last name (nom)
     * @param string $lastname
     * @return $this;
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return int
     */
    public function getMailingActu()
    {
        return $this->mailingActu;
    }

    /**
     * @param int $mailingActu
     */
    public function setMailingActu($mailingActu)
    {
        $this->mailingActu = $mailingActu;
    }

    /**
     * @return int
     */
    public function getMailingNeobe()
    {
        return $this->mailingNeobe;
    }

    /**
     * @param int $mailingNeobe
     */
    public function setMailingNeobe($mailingNeobe)
    {
        $this->mailingNeobe = $mailingNeobe;
    }

    /**
     * @return int
     */
    public function getMailingPromo()
    {
        return $this->mailingPromo;
    }

    /**
     * @param int $mailingPromo
     */
    public function setMailingPromo($mailingPromo)
    {
        $this->mailingPromo = $mailingPromo;
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
    public function getNbCredit()
    {
        return $this->nbCredit;
    }

    /**
     * @param int $nbCredit
     */
    public function setNbCredit($nbCredit)
    {
        $this->nbCredit = $nbCredit;
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
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getSignUpToken()
    {
        return $this->signUpToken;
    }

    /**
     * @param string $signUpToken
     */
    public function setSignUpToken($signUpToken)
    {
        $this->signUpToken = $signUpToken;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }



}
