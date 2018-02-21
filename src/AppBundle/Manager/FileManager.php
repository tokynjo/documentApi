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

    /**
     * @param $user
     * @return mixed
     */
    public function getStructureInterne($user)
    {
        return $this->repository->getFilesByUser($user);
    }

    /**
     * @param $user
     * @return mixed
     */
    public function getStructureExterne($user)
    {
        return $this->repository->getFilesInvitRequest($user);
    }

    /**
     * get taille total in folder_id
     * @param $user
     * @return mixed
     */
    public function getTailleTotal($idFolder)
    {
        $totat = $this->repository->getTailleTotal($idFolder);
        return (isset($totat[0]) ? $totat[0] : 0);
    }

    public function getInfosUSer($id_file)
    {
        $totat = $this->repository->getInfosUser($id_file);
        return (isset($totat[0]) ? $totat[0] : 0);
    }

}
