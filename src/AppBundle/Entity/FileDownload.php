<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity for the table my_fichier_download
 *
 * @package AppBundle\Entity
 *
 * @ORM\Table(name="my_fichier_download")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FileDownloadRepository")
 */
class FileDownload
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
     * @ORM\ManyToOne(targetEntity="File", inversedBy="fileDownloads", cascade={"persist"})
     * @ORM\JoinColumn(name="id_file", referencedColumnName="id")
     */
    private $file;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=255, nullable=true)
     */
    private $ip;

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
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;



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

}
