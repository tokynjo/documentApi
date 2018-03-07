<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Category
 * category off client
 *
 * @ORM\Table(name="my_category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 */
class Category
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
     * @ORM\Column(name="label", type="string", length=255)
     */
    private $label;

    /**
     * @ORM\OneToMany(targetEntity="Client", mappedBy="category", cascade={"persist"})
     */
    private $clients;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->clients = new ArrayCollection();
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

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
     * Set label
     *
     * @param string label
     *
     * @return ClientType
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     * @return $this
     */
    public function getLabel()
    {
        return $this->label;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClients()
    {
        return $this->clients;
    }

    /**
     * Add client
     *
     * @param Client $client
     *
     * @return $this
     */
    public function addClient(Client $client)
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
}
