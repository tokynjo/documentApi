<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EmailAutomatique
 *
 * @ORM\Table(name="modele_email")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\EmailAutomatiqueRepository")
 */
class EmailAutomatique
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="objet", type="string", length=255)
     */
    private $objet;

    /**
     * @var int
     *
     * @ORM\Column(name="declenchement", type="integer")
     */
    private $declenchement;

    /**
     * @var bool
     *
     * @ORM\Column(name="etat", type="boolean")
     */
    private $etat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="template", type="string", length=2000, nullable=true)
     */
    private $template;


    /**
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User", inversedBy="mailConfig", cascade={"persist"})
     * @ORM\JoinColumn(name="id_expediteur", referencedColumnName="id")
     */
    private $emitter;

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
     * Set name
     *
     * @param string $name
     *
     * @return EmailAutomatique
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set objet
     *
     * @param string $objet
     *
     * @return EmailAutomatique
     */
    public function setObjet($objet)
    {
        $this->objet = $objet;

        return $this;
    }

    /**
     * Get objet
     *
     * @return string
     */
    public function getObjet()
    {
        return $this->objet;
    }


    /**
     * Set declenchement
     *
     * @param integer $declenchement
     *
     * @return EmailAutomatique
     */
    public function setDeclenchement($declenchement)
    {
        $this->declenchement = $declenchement;

        return $this;
    }

    /**
     * Get declenchement
     *
     * @return int
     */
    public function getDeclenchement()
    {
        return $this->declenchement;
    }

    /**
     * Set etat
     *
     * @param boolean $etat
     *
     * @return EmailAutomatique
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return bool
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * EmailAutomatique constructor.
     */
    public function __construct()
    {
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return EmailAutomatique
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }


    /**
     * Set template
     *
     * @param string $template
     *
     * @return EmailAutomatique
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     *
     * @return EmailAutomatique
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Set emitter
     *
     * @param \ApiBundle\Entity\User $emitter
     *
     * @return EmailAutomatique
     */
    public function setEmitter(\ApiBundle\Entity\User $emitter = null)
    {
        $this->emitter = $emitter;

        return $this;
    }

    /**
     * Get emitter
     *
     * @return \ApiBundle\Entity\User
     */
    public function getEmitter()
    {
        return $this->emitter;
    }
}
