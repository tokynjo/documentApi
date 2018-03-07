<?php

namespace AppBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;

class ClientManager extends BaseManager
{
    const SERVICE_NAME = 'app.client_manager';

    public function __construct(EntityManagerInterface $entityManager, $class)
    {
        parent::__construct($entityManager, $class);
    }
}
