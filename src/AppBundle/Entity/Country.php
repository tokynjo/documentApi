<?php
namespace AppBundle\Entity;

use AppBundle\Entity\Client;
use Doctrine\ORM\Mapping as ORM;

/**
 *  country
 *
 * @ORM\Table(name="my_country")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CountryRepository")
 */
class Country
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="string", length=3, unique=true)
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255)
     */
    private $label;

    /**
     * @var int
     *
     * @ORM\Column(name="ue", type="integer")
     */
    private $uE;

    /**
     * @var string
     *
     * @ORM\Column(name="id_currency", type="string", length=255)
     */
    private $idCurrency;

    /**
     * @var int
     *
     * @ORM\Column(name="country_phone_code", type="integer")
     */
    private $countryPhoneCode;

    /**
     * @var string
     *
     * @ORM\Column(name="lang", type="string", length=255)
     */
    private $lang;

    /**
     * @var string
     *
     * @ORM\Column(name="display_flag", type="string", length=255)
     */
    private $displayFlag;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_card", type="string", length=255)
     */
    private $paymentCard;

    /**
     * @var float
     *
     * @ORM\Column(name="vat_rate", type="float")
     */
    private $vatRate;


    /**
     * @ORM\OneToMany(targetEntity="Client", mappedBy="country", cascade={"persist"})
     */
    private $clients;

/**
     * Constructor
     */
    public function __construct()
    {
        $this->clients = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return int
     */
    public function getCountryPhoneCode()
    {
        return $this->countryPhoneCode;
    }

    /**
     * @param int $countryPhoneCode
     * @return $this
     */
    public function setCountryPhoneCode($countryPhoneCode)
    {
        $this->countryPhoneCode = $countryPhoneCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getDisplayFlag()
    {
        return $this->displayFlag;
    }

    /**
     * @param string $displayFlag
     * @return $this;
     */
    public function setDisplayFlag($displayFlag)
    {
        $this->displayFlag = $displayFlag;
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
     * @return string
     */
    public function getIdCurrency()
    {
        return $this->idCurrency;
    }

    /**
     * @param string $idCurrency
     * @return $this
     */
    public function setIdCurrency($idCurrency)
    {
        $this->idCurrency = $idCurrency;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param string $lang
     * @return $this
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentCard()
    {
        return $this->paymentCard;
    }

    /**
     * @param string $paymentCard
     * @return $this;
     */
    public function setPaymentCard($paymentCard)
    {
        $this->paymentCard = $paymentCard;
        return $this;
    }

    /**
     * @return int
     */
    public function getUE()
    {
        return $this->uE;
    }

    /**
     * @param int $uE
     * @return $this
     */
    public function setUE($uE)
    {
        $this->uE = $uE;
        return $this;
    }

    /**
     * @return float
     */
    public function getVatRate()
    {
        return $this->vatRate;
    }

    /**
     * set Value Added Tax rate
     * @param float $vatRate
     * @return $this
     */
    public function setVatRate($vatRate)
    {
        $this->vatRate = $vatRate;
        return $this;
    }

/**
     * Add client
     *
     * @param $client
     *
     * @return $this
     */
    public function addClient(Client$client)
    {
        $this->clients[] = $client;

        return $this;
    }

    /**
     * Remove client
     *
     * @param \AppBundle\Entity\Client $client
     */
    public function removeClient(\AppBundle\Entity\Client $client)
    {
        $this->clients->removeElement($client);
    }

    /**
     * Get clients
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getClients()
    {
        return $this->clients;
    }

    /**
     * Set id
     *
     * @param string $id
     *
     * @return Country
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
