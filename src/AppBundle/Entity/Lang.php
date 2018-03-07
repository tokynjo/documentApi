<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Lang, ORM entity for the table "my_langue"
 *
 * @ORM\Table(name="my_langue")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LangRepository")
 */
class Lang
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\UserPreference" , mappedBy="lang" , cascade={"all"})
     */
    private $userPreferences;

    /**
     * constructor
     * @return Lang
     */
    public function __construct()
    {
        $this->userPreferences = new ArrayCollection();
        return $this;
    }

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
     * Set label
     *
     * @param string $label
     *
     * @return View
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
     * Add userPreference
     *
     * @param \AppBundle\Entity\UserPreference $userPreference
     *
     * @return Lang
     */
    public function addUserPreference(\AppBundle\Entity\UserPreference $userPreference)
    {
        $this->userPreferences[] = $userPreference;

        return $this;
    }

    /**
     * Remove userPreference
     *
     * @param \AppBundle\Entity\UserPreference $userPreference
     */
    public function removeUserPreference(\AppBundle\Entity\UserPreference $userPreference)
    {
        $this->userPreferences->removeElement($userPreference);
    }

    /**
     * Get userPreferences
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserPreferences()
    {
        return $this->userPreferences;
    }
}
