<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClientType
 *
 * @ORM\Table(name="my_client_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClientTypeRepository")
 */
class ClientType
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
     * @var string
     *
     * @ORM\Column(name="id_langues", type="string", length=5)
     */
    private $idLangues;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=2)
     */
    private $type;


    /**
     * @ORM\OneToMany(targetEntity="Client", mappedBy="clientType", cascade={"persist"})
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
     * @return ClientType
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
     * Set idLangues
     *
     * @param string $idLangues
     *
     * @return ClientType
     */
    public function setIdLangues($idLangues)
    {
        $this->idLangues = $idLangues;

        return $this;
    }

    /**
     * Get idLangues
     *
     * @return string
     */
    public function getIdLangues()
    {
        return $this->idLangues;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return ClientType
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
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
     * @return ClientType
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
