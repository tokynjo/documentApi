<?php

namespace ApiBundle\Manager;

use AppBundle\Manager\BaseManager;
use Doctrine\ORM\EntityManagerInterface;

/**
 *
 * Querying UserManager
 */
class UserManager extends BaseManager
{
    const SERVICE_NAME = 'app.user_manager';

    public function __construct(EntityManagerInterface $entityManager, $class)
    {
        parent::__construct($entityManager, $class);
    }

}
