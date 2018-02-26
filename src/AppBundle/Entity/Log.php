<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Entity for the table my_log_type
 *
 * @package AppBundle\Entity
 *
 * @ORM\Table(name="my_log")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LogRepository")
 */
class Log
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
     *
     * @ORM\ManyToOne(targetEntity="LogAction", inversedBy="logs", cascade={"persist"})
     * @ORM\JoinColumn(name="id_log_action", referencedColumnName="id")
     */
    private $logAction;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="logs", cascade={"persist"})
     * @ORM\JoinColumn(name="id_client", referencedColumnName="id")
     */
    private $client;

    /**
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User", inversedBy="logs", cascade={"persist"})
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var text
     *
     * @ORM\Column(name="url", type="text")
     */
    private $url;

    /**
     * @var text
     *
     * @ORM\Column(name="referer", type="text")
     */
    private $referer;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=255, nullable=true)
     */
    private $ip;

    /**
     * @var int
     *
     * @ORM\Column(name="id_object", type="integer", length=11, nullable=true)
     */
    private $idObject;

    /**
     * @var text
     *
     * @ORM\Column(name="user_agent", type="text", nullable=true)
     */
    private $userAgent;

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
