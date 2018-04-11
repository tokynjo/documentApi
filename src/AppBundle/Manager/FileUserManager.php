<?php

namespace AppBundle\Manager;

use ApiBundle\Entity\User;
use AppBundle\Entity\File;
use Doctrine\ORM\EntityManagerInterface;

class FileUserManager extends BaseManager
{
    const SERVICE_NAME = 'app.file_user_manager';

    /**
     * FileUserManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param type                   $class
     */
    public function __construct(EntityManagerInterface $entityManager, $class)
    {
        parent::__construct($entityManager, $class);
    }

    /**
     * @param User $user
     *
     * @return mixed
     */
    public function getStructureInterne(User $user)
    {
        return $this->repository->getFilesByUser($user);
    }

    /**
     * @param integer $id
     *
     * @return array
     */
    public function getInvites($id)
    {
        return $this->repository->getInvitationByFile($id);
    }

    /**
     * @param File  $file
     * @param User  $user
     * @param array $tabRight
     *
     * @return bool
     */
    public function getRightUser(File $file, User $user, array $tabRight)
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

    /**
     * @param integer $idFile
     */
    public function findNotExpired($idFile){

        return $this->repository->findNotExpired($idFile);
    }
}
