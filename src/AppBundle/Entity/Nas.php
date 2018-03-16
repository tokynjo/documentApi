<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Nas
 *
 * @ORM\Table(name="my_nas")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NasRepository")
 */
class Nas
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
     * @ORM\Column(name="libelle", type="string", length=7)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="absolutePath", type="string", length=255)
     */
    private $absolutePath;

    /**
     * @var int
     *
     * @ORM\Column(name="selected", type="integer")
     */
    private $selected;

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
     * @return Nas
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
     * Set absolutePath
     *
     * @param string $absolutePath
     *
     * @return Nas
     */
    public function setAbsolutePath($absolutePath)
    {
        $this->absolutePath = $absolutePath;

        return $this;
    }

    /**
     * Get absolutePath
     *
     * @return string
     */
    public function getAbsolutePath()
    {
        return $this->absolutePath;
    }

    /**
     * Set selected
     *
     * @param integer $selected
     *
     * @return Nas
     */
    public function setSelected($selected)
    {
        $this->selected = $selected;

        return $this;
    }

    /**
     * Get selected
     *
     * @return int
     */
    public function getSelected()
    {
        return $this->selected;
    }
}
