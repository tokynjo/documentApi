<?php

namespace AppBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;

class FileUserManager extends BaseManager
{
    const SERVICE_NAME = 'app.file_user_manager';

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
        return $this->repository->getInvitationByFile($id);
    }

    /**
     * @param $folder
     * @param $user
     * @return bool
     */
    public function getRightUser($file, $user,$tabRight)
    {
        if ($user == $file->getUser()) {
            return true;
        } else {
            if ($right = $this->repository->getRightUser($user, $file)) {
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
