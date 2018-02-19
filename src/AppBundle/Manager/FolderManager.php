<?php

namespace AppBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;

class FolderManager extends BaseManager
{
    const SERVICE_NAME = 'app.folder_manager';

    public function __construct(EntityManagerInterface $entityManager, $class)
    {
        parent::__construct($entityManager, $class);
    }

    public function getStructure($user){
        $data["interne"]["folders"] = $this->repository->getFolderByUser($user);
        $data["externe"]["folders"] = $this->repository->getFolderInvitRequest($user);
        return $data;
    }

}
