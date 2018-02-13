<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dossiers
 *
 * @ORM\Table(name="my_fichiers")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FichiersRepository")
 */
class Fichiers
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
     * @ORM\Column(name="hash", type="text" )
     */
    private $hash;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_symbolique", type="string", length=255)
     */
    private $nomSymbolique;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="taille", type="float")
     */
    private $taille;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Dossiers", inversedBy="fichiers", cascade={"persist"})
     * @ORM\JoinColumn(name="id_dossier", referencedColumnName="id")
     */
    private $dossier;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiration", type="datetime")
     */
    private $expiration;

    /**
     * @var text
     *
     * @ORM\Column(name="commentaires", type="text", nullable=true)
     */
    private $commentaires;

    /**
     * @var int
     * @ORM\Column(columnDefinition="TINYINT DEFAULT 0 NOT NULL")
     * @ORM\Column(name="cryptage", type="integer", length=11)
     */
    private $cryptage;

    /**
     * @var string
     *
     * @ORM\Column(name="permalink", type="string", length=255, nullable=true)
     */
    private $permalien;

    /**
     * @var int
     *
     * @ORM\Column(name="statut", type="integer")
     */
    private $statut;

    /**
     * @var string
     *
     * @ORM\Column(name="checksum", type="string", length=255, nullable=true)
     */
    private $checksum;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime", nullable=true)
     */
    private $updatedAt;
    /**
     * @var string
     *
     * @ORM\Column(name="deletedAt", type="datetime", length=255, nullable=true)
     */
    private $deletedAt;

    /**
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User", inversedBy="fichiersDeleted", cascade={"persist"})
     * @ORM\JoinColumn(name="id_deleted_by", referencedColumnName="id")
     */
    private $deletedBy;

    /**
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User", inversedBy="fichiersUser", cascade={"persist"})
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     */
    private $user;

    /**
     * @var int
     * @ORM\Column(columnDefinition="TINYINT DEFAULT 0 NOT NULL")
     * @ORM\Column(name="share", type="integer")
     */
    private $share;

    /**
     * @var string
     *
     * @ORM\Column(name="sharePassword", type="string", length=255, nullable=true)
     */
    private $sharePassword;

    /**
     * @var int
     *
     * @ORM\Column(name="favorite", type="integer", nullable=true)
     */
    private $favorite;

    /**
     * @var int
     *
     * @ORM\Column(name="direct_permalink_enabled", type="integer", nullable=true)
     */
    private $directPermalinkEnabled;

    /**
     * @var int
     *
     * @ORM\Column(name="locked", type="integer")
     */
    private $locked;


    /**
     * @var int
     *
     * @ORM\Column(name="archive_file_id", type="integer")
     */
    private $archiveFileId;



    /**
     * @ORM\OneToMany(targetEntity="FichierHasUser", mappedBy="fichier", cascade={"persist"})
     */
    private $fichierHasUsers;

    /**
     *
     * @ORM\ManyToOne(targetEntity="InvitationHasUserRequest", inversedBy="fichier", cascade={"persist"})
     */
    private $invitationHasUserRequests;

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
     * Set hash
     *
     * @param string $hash
     *
     * @return Fichiers
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set nomSymbolique
     *
     * @param string $nomSymbolique
     *
     * @return Fichiers
     */
    public function setNomSymbolique($nomSymbolique)
    {
        $this->nomSymbolique = $nomSymbolique;

        return $this;
    }

    /**
     * Get nomSymbolique
     *
     * @return string
     */
    public function getNomSymbolique()
    {
        return $this->nomSymbolique;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Fichiers
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set taille
     *
     * @param float $taille
     *
     * @return Fichiers
     */
    public function setTaille($taille)
    {
        $this->taille = $taille;

        return $this;
    }

    /**
     * Get taille
     *
     * @return float
     */
    public function getTaille()
    {
        return $this->taille;
    }

    /**
     * Set expiration
     *
     * @param \DateTime $expiration
     *
     * @return Fichiers
     */
    public function setExpiration($expiration)
    {
        $this->expiration = $expiration;

        return $this;
    }

    /**
     * Get expiration
     *
     * @return \DateTime
     */
    public function getExpiration()
    {
        return $this->expiration;
    }

    /**
     * Set commentaires
     *
     * @param string $commentaires
     *
     * @return Fichiers
     */
    public function setCommentaires($commentaires)
    {
        $this->commentaires = $commentaires;

        return $this;
    }

    /**
     * Get commentaires
     *
     * @return string
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }

    /**
     * Set cryptage
     *
     * @param string $cryptage
     *
     * @return Fichiers
     */
    public function setCryptage($cryptage)
    {
        $this->cryptage = $cryptage;

        return $this;
    }

    /**
     * Get cryptage
     *
     * @return string
     */
    public function getCryptage()
    {
        return $this->cryptage;
    }

    /**
     * Set permalien
     *
     * @param string $permalien
     *
     * @return Fichiers
     */
    public function setPermalien($permalien)
    {
        $this->permalien = $permalien;

        return $this;
    }

    /**
     * Get permalien
     *
     * @return string
     */
    public function getPermalien()
    {
        return $this->permalien;
    }

    /**
     * Set statut
     *
     * @param integer $statut
     *
     * @return Fichiers
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return integer
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set checksum
     *
     * @param string $checksum
     *
     * @return Fichiers
     */
    public function setChecksum($checksum)
    {
        $this->checksum = $checksum;

        return $this;
    }

    /**
     * Get checksum
     *
     * @return string
     */
    public function getChecksum()
    {
        return $this->checksum;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Fichiers
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     *
     * @return Fichiers
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
     * Set share
     *
     * @param string $share
     *
     * @return Fichiers
     */
    public function setShare($share)
    {
        $this->share = $share;

        return $this;
    }

    /**
     * Get share
     *
     * @return string
     */
    public function getShare()
    {
        return $this->share;
    }

    /**
     * Set sharePassword
     *
     * @param string $sharePassword
     *
     * @return Fichiers
     */
    public function setSharePassword($sharePassword)
    {
        $this->sharePassword = $sharePassword;

        return $this;
    }

    /**
     * Get sharePassword
     *
     * @return string
     */
    public function getSharePassword()
    {
        return $this->sharePassword;
    }

    /**
     * Set favorite
     *
     * @param integer $favorite
     *
     * @return Fichiers
     */
    public function setFavorite($favorite)
    {
        $this->favorite = $favorite;

        return $this;
    }

    /**
     * Get favorite
     *
     * @return integer
     */
    public function getFavorite()
    {
        return $this->favorite;
    }

    /**
     * Set directPermalinkEnabled
     *
     * @param integer $directPermalinkEnabled
     *
     * @return Fichiers
     */
    public function setDirectPermalinkEnabled($directPermalinkEnabled)
    {
        $this->directPermalinkEnabled = $directPermalinkEnabled;

        return $this;
    }

    /**
     * Get directPermalinkEnabled
     *
     * @return integer
     */
    public function getDirectPermalinkEnabled()
    {
        return $this->directPermalinkEnabled;
    }

    /**
     * Set locked
     *
     * @param integer $locked
     *
     * @return Fichiers
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;

        return $this;
    }

    /**
     * Get locked
     *
     * @return integer
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * Set dossier
     *
     * @param \AppBundle\Entity\Dossiers $dossier
     *
     * @return Fichiers
     */
    public function setDossier(\AppBundle\Entity\Dossiers $dossier = null)
    {
        $this->dossier = $dossier;

        return $this;
    }

    /**
     * Get dossier
     *
     * @return \AppBundle\Entity\Dossiers
     */
    public function getDossier()
    {
        return $this->dossier;
    }

    /**
     * Set deletedBy
     *
     * @param \ApiBundle\Entity\User $deletedBy
     *
     * @return Fichiers
     */
    public function setDeletedBy(\ApiBundle\Entity\User $deletedBy = null)
    {
        $this->deletedBy = $deletedBy;

        return $this;
    }

    /**
     * Get deletedBy
     *
     * @return \ApiBundle\Entity\User
     */
    public function getDeletedBy()
    {
        return $this->deletedBy;
    }

    /**
     * Set archiveFileId
     *
     * @param integer $archiveFileId
     *
     * @return Fichiers
     */
    public function setArchiveFileId($archiveFileId)
    {
        $this->archiveFileId = $archiveFileId;

        return $this;
    }

    /**
     * Get archiveFileId
     *
     * @return integer
     */
    public function getArchiveFileId()
    {
        return $this->archiveFileId;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fichierHasUsers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set user
     *
     * @param \ApiBundle\Entity\User $user
     *
     * @return Fichiers
     */
    public function setUser(\ApiBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \ApiBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add fichierHasUser
     *
     * @param \AppBundle\Entity\FichierHasUser $fichierHasUser
     *
     * @return Fichiers
     */
    public function addFichierHasUser(\AppBundle\Entity\FichierHasUser $fichierHasUser)
    {
        $this->fichierHasUsers[] = $fichierHasUser;

        return $this;
    }

    /**
     * Remove fichierHasUser
     *
     * @param \AppBundle\Entity\FichierHasUser $fichierHasUser
     */
    public function removeFichierHasUser(\AppBundle\Entity\FichierHasUser $fichierHasUser)
    {
        $this->fichierHasUsers->removeElement($fichierHasUser);
    }

    /**
     * Get fichierHasUsers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFichierHasUsers()
    {
        return $this->fichierHasUsers;
    }

    /**
     * Set invitationHasUserRequests
     *
     * @param \AppBundle\Entity\InvitationHasUserRequest $invitationHasUserRequests
     *
     * @return Fichiers
     */
    public function setInvitationHasUserRequests(\AppBundle\Entity\InvitationHasUserRequest $invitationHasUserRequests = null)
    {
        $this->invitationHasUserRequests = $invitationHasUserRequests;

        return $this;
    }

    /**
     * Get invitationHasUserRequests
     *
     * @return \AppBundle\Entity\InvitationHasUserRequest
     */
    public function getInvitationHasUserRequests()
    {
        return $this->invitationHasUserRequests;
    }
}
