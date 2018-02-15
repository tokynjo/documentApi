<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class UserPreference, ORM entity for the table "my_user_preference"
 *
 * @ORM\Table(name="my_user_preference")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserPreferenceRepository")
 */
class UserPreference
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
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User", inversedBy="preferences")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     *
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\View", inversedBy="userPreferences")
     * @ORM\JoinColumn(name="id_vue", referencedColumnName="id")
     *
     */
    private $view;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Lang", inversedBy="userPreferences")
     * @ORM\JoinColumn(name="id_langue", referencedColumnName="id")
     *
     */
    private $lang;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return mixed
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param mixed $lang
     * @return $this
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
        return $this;
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
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @param mixed $view
     * @return $this
     */
    public function setView($view)
    {
        $this->view = $view;
        return $this;
    }



}
