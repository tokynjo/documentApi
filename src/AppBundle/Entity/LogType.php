<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity for the table my_log_type
 *
 * @package AppBundle\Entity
 *
 * @ORM\Table(name="my_log_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LogTypeRepository")
 */
class LogType
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
     * @ORM\OneToMany(targetEntity="LogAction", mappedBy="logType", cascade={"persist"})
     */
    private $logActions;

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
     * Add logAction
     *
     * @param \AppBundle\Entity\LogAction $logAction
     *
     * @return LogType
     */
    public function addLogAction(\AppBundle\Entity\LogAction $logAction)
    {
        $this->logActions[] = $logAction;

        return $this;
    }

    /**
     * Remove logAction
     *
     * @param \AppBundle\Entity\LogAction $logAction
     */
    public function removeLogAction(\AppBundle\Entity\LogAction $logAction)
    {
        $this->logActions->removeElement($logAction);
    }

    /**
     * Get logActions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLogActions()
    {
        return $this->logActions;
    }
}
