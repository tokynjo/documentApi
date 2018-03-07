<?php

namespace AppBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;

class UserManager extends BaseManager
{
    const SERVICE_NAME = 'app.user_manager';

    public function __construct(EntityManagerInterface $entityManager, $class)
    {
        parent::__construct($entityManager, $class);
    }



}
