<?php
namespace AppBundle\Manager;

use ApiBundle\Entity\User;
use AppBundle\Entity\Constants\Constant;
use AppBundle\Entity\Folder;
use Doctrine\ORM\EntityManagerInterface;

class FolderManager extends BaseManager
{
    const SERVICE_NAME = 'app.folder_manager';

    public function __construct(EntityManagerInterface $entityManager, $class)
    {
        parent::__construct($entityManager, $class);
    }

    /**
     * @param $user
     * @return mixed
     */
    public function getStructure($user, $id_folder = null)
    {
        if($id_folder == null){
            $data["interne"]["folders"] = $this->repository->getFolderByUser($user);
            $data["externe"]["folders"] = $this->repository->getFolderInvitRequest($user);
        }else{
            $data["interne"]["folders"] = $this->repository->getFolderByUserIdFolder($user,$id_folder);
         //   $data["externe"]["folders"] = $this->repository->getFolderExterne($user,$id_folder);
        }
        return $data;

    }

    /**
     * @param $id
     * @return array
     */
    public function getInfosUser($id)
    {
        $result = $this->repository->getFolderById($id);
        return (($result == 0) ? [] : $result);
    }

    public function lockFolder (Folder $folder, User $user)
    {
        $return = false;
        $_folder = $this->repository->findFolderLockableByUser($folder, $user);
        if ($_folder && $folder->getLocked() == Constant::NOT_LOCKED) {
            $folder->setLocked(Constant::LOCKED);
            $this->saveAndFlush($folder);
            //save log

            $return = true;
        }
        return $return;
    }
}
