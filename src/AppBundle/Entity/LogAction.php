<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity for the table my_log_action
 *
 * @package AppBundle\Entity
 *
 * @ORM\Table(name="my_log_action")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LogActionRepository")
 */
class LogAction
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
     * @ORM\ManyToOne(targetEntity="LogType", inversedBy="logActions", cascade={"persist"})
     * @ORM\JoinColumn(name="id_log_type", referencedColumnName="id")
     */
    private $logType;

    /**
     * @ORM\OneToMany(targetEntity="Log", mappedBy="logAction", cascade={"persist"})
     */
    private $logs;

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
     * @return mixed
     */
    public function getLogType()
    {
        return $this->logType;
    }

    /**
     * @param mixed $logType
     */
    public function setLogType($logType)
    {
        $this->logType = $logType;
    }
}
