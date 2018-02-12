<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClientPays
 *
 * @ORM\Table(name="my_clients_pays")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClientPaysRepository")
 */
class ClientPays
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
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;

    /**
     * @var int
     *
     * @ORM\Column(name="UE", type="integer")
     */
    private $uE;

    /**
     * @var string
     *
     * @ORM\Column(name="id_devise", type="string", length=255)
     */
    private $idDevise;

    /**
     * @var int
     *
     * @ORM\Column(name="indicatif", type="integer")
     */
    private $indicatif;

    /**
     * @var string
     *
     * @ORM\Column(name="langue", type="string", length=255)
     */
    private $langue;

    /**
     * @var string
     *
     * @ORM\Column(name="aff_drapeau", type="string", length=255)
     */
    private $affDrapeau;

    /**
     * @var string
     *
     * @ORM\Column(name="paiement_carte", type="string", length=255)
     */
    private $paiementCarte;

    /**
     * @var float
     *
     * @ORM\Column(name="taux_tva", type="float")
     */
    private $tauxTva;


    /**
     * @ORM\OneToMany(targetEntity="Client", mappedBy="clientPays", cascade={"persist"})
     */
    private $clients;
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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return ClientPays
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set uE
     *
     * @param integer $uE
     *
     * @return ClientPays
     */
    public function setUE($uE)
    {
        $this->uE = $uE;

        return $this;
    }

    /**
     * Get uE
     *
     * @return int
     */
    public function getUE()
    {
        return $this->uE;
    }

    /**
     * Set idDevise
     *
     * @param string $idDevise
     *
     * @return ClientPays
     */
    public function setIdDevise($idDevise)
    {
        $this->idDevise = $idDevise;

        return $this;
    }

    /**
     * Get idDevise
     *
     * @return string
     */
    public function getIdDevise()
    {
        return $this->idDevise;
    }

    /**
     * Set indicatif
     *
     * @param integer $indicatif
     *
     * @return ClientPays
     */
    public function setIndicatif($indicatif)
    {
        $this->indicatif = $indicatif;

        return $this;
    }

    /**
     * Get indicatif
     *
     * @return int
     */
    public function getIndicatif()
    {
        return $this->indicatif;
    }

    /**
     * Set langue
     *
     * @param string $langue
     *
     * @return ClientPays
     */
    public function setLangue($langue)
    {
        $this->langue = $langue;

        return $this;
    }

    /**
     * Get langue
     *
     * @return string
     */
    public function getLangue()
    {
        return $this->langue;
    }

    /**
     * Set affDrapeau
     *
     * @param string $affDrapeau
     *
     * @return ClientPays
     */
    public function setAffDrapeau($affDrapeau)
    {
        $this->affDrapeau = $affDrapeau;

        return $this;
    }

    /**
     * Get affDrapeau
     *
     * @return string
     */
    public function getAffDrapeau()
    {
        return $this->affDrapeau;
    }

    /**
     * Set paiementCarte
     *
     * @param string $paiementCarte
     *
     * @return ClientPays
     */
    public function setPaiementCarte($paiementCarte)
    {
        $this->paiementCarte = $paiementCarte;

        return $this;
    }

    /**
     * Get paiementCarte
     *
     * @return string
     */
    public function getPaiementCarte()
    {
        return $this->paiementCarte;
    }

    /**
     * Set tauxTva
     *
     * @param float $tauxTva
     *
     * @return ClientPays
     */
    public function setTauxTva($tauxTva)
    {
        $this->tauxTva = $tauxTva;

        return $this;
    }

    /**
     * Get tauxTva
     *
     * @return float
     */
    public function getTauxTva()
    {
        return $this->tauxTva;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->clients = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add client
     *
     * @param \AppBundle\Entity\Client $client
     *
     * @return ClientPays
     */
    public function addClient(\AppBundle\Entity\Client $client)
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
}
