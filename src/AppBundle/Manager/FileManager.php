<?php

namespace AppBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;

class FileManager extends BaseManager
{
    const SERVICE_NAME = 'app.file_manager';

    public function __construct(EntityManagerInterface $entityManager, $class)
    {
        parent::__construct($entityManager, $class);
    }

    public function getStructureInterne($user)
    {
        return $this->repository->getFilesByUser($user);
    }

    public function getStructureExterne($user)
    {
        return $this->repository->getFilesInvitRequest($user);
    }

}
