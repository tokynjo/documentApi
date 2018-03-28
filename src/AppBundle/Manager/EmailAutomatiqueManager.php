<?php

namespace AppBundle\Manager;

use AdminBundle\Entity\EmailAutomatique;
use Doctrine\ORM\EntityManagerInterface;


/**
 *

 */
class EmailAutomatiqueManager extends BaseManager
{
    const SERVICE_NAME = 'app.mail_automatique_manager';

    /**
     * EmailAutomatiqueManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     *
     * @param type                   $class
     */
    public function __construct(EntityManagerInterface $entityManager, $class)
    {
        parent::__construct($entityManager, $class);
    }

    /**
     * @param EmailAutomatique $mail
     *
     * @return EmailAutomatique
     */
    public function create(EmailAutomatique $mail)
    {
        $this->save($mail);
        $this->flushAndClear();

        return $mail;
    }

    /**
     * @param array  $dataModel
     * @param array  $data
     * @param string $template
     *
     * @return mixed
     */
    public static function replaceData($dataModel, $data, $template)
    {
        return str_replace($dataModel, $data, $template);
    }
}
