<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity for the table my_fichier_log_action
 *
 * @package AppBundle\Entity
 *
 * @ORM\Table(name="my_fichier_log_action")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FileLogActionRepository")
 */
class FileLogAction
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
    private $label;

    /**
     *
     * @ORM\OneToMany(targetEntity="FileLog", mappedBy="fileLogAction", cascade={"persist"})
     */
    private $fileLogs;

/**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }



    public function getId()
    {
        return $this->id;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
    }
}
