<?php

namespace AppBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;

class FolderUserManager extends BaseManager
{
    const SERVICE_NAME = 'app.folder_user_manager';

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
     * @param $id
     * @return array
     */
    public function getInvites($id)
    {
        return $this->repository->getInvitationByFolder($id);
    }

    /**
     * @param $folder
     * @param $user
     * @return bool
     */
    public function getRightUser($folder, $user,$tabRight)
    {
        if ($user == $folder->getUser()) {
            return true;
        } else {
            if ($right = $this->repository->getRightUser($user, $folder)) {
                if (isset($right[0])) {
                    if (in_array($right[0]["id"], $tabRight)) {
                        return true;
                    }
                }
            }
            return false;
        }
    }
}
