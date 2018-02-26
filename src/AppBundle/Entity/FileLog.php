<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity for the table my_fichier_log
 *
 * @package AppBundle\Entity
 *
 * @ORM\Table(name="my_fichier_log")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FileLogRepository")
 */
class FileLog
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
     * @ORM\ManyToOne(targetEntity="File", inversedBy="fileLogs", cascade={"persist"})
     * @ORM\JoinColumn(name="id_fichier", referencedColumnName="id")
     */
    private $file;

    /**
     *
     * @ORM\ManyToOne(targetEntity="FileLogAction", inversedBy="fileLogs", cascade={"persist"})
     * @ORM\JoinColumn(name="id_file_log_action", referencedColumnName="id")
     */
    private $fileLogAction;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="fileLogs", cascade={"persist"})
     * @ORM\JoinColumn(name="id_client", referencedColumnName="id")
     */
    private $client;

    /**
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User", inversedBy="fileLogs", cascade={"persist"})
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
     * @ORM\Column(name="url", type="text", nullable=true)
     */
    private $url;

    /**
     * @var text
     *
     * @ORM\Column(name="referer", type="text", nullable=true)
     */
    private $referer;

    /**
     * @var text
     *
     * @ORM\Column(name="user_agent", type="text", nullable=true)
     */
    private $userAgent;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=255, nullable=true)
     */
    private $ip;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Folder", inversedBy="fileLogs", cascade={"persist"})
     * @ORM\JoinColumn(name="to_folder", referencedColumnName="id")
     */
    private $folder;


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
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param mixed $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * @param mixed $folder
     */
    public function setFolder($folder)
    {
        $this->folder = $folder;
    }

    /**
     * @return mixed
     */
    public function getFolderLogAction()
    {
        return $this->folderLogAction;
    }

    /**
     * @param mixed $folderLogAction
     */
    public function setFolderLogAction($folderLogAction)
    {
        $this->folderLogAction = $folderLogAction;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return text
     */
    public function getReferer()
    {
        return $this->referer;
    }

    /**
     * @param text $referer
     */
    public function setReferer($referer)
    {
        $this->referer = $referer;
    }

    /**
     * @return text
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param text $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return text
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @param text $userAgent
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
    }



}
