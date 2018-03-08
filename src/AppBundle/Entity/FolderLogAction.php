<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity for the table my_dossier_log_action
 *
 * @package AppBundle\Entity
 *
 * @ORM\Table(name="my_dossier_log_action")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FolderLogActionRepository")
 */
class FolderLogAction
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
     * @ORM\OneToMany(targetEntity="FolderLog", mappedBy="folderLogAction", cascade={"persist"})
     */
    private $folderLogs;

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

    /**
     * Add folderLog
     *
     * @param \AppBundle\Entity\FolderLog $folderLog
     *
     * @return FolderLogAction
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
}
