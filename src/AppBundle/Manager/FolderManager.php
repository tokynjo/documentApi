<?php
namespace AppBundle\Manager;

use ApiBundle\Entity\User;
use AppBundle\Entity\Api\ApiResponse;
use AppBundle\Entity\Constants\Constant;
use AppBundle\Entity\Folder;
use AppBundle\Event\FolderEvent;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;

class FolderManager extends BaseManager
{
    const SERVICE_NAME = 'app.folder_manager';

    public function __construct(
        EntityManagerInterface $entityManager,
        $class
    )
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

    /** lock folder
     * @author Olonash
     * @param Folder $folder
     * @param User $user
     * @return bool
     */
    public function lockFolder (Folder $folder, User $user)
    {
        $resp = Response::HTTP_OK;
        $_folder = $this->repository->findFolderLockableByUser($folder, $user);
        if ($_folder ) {
            if($folder->getLocked() == Constant::NOT_LOCKED) {
                $folder->setLocked(Constant::LOCKED);
                $this->saveAndFlush($folder);
            } else {
                $resp = Response::HTTP_ACCEPTED;
            }
        } else {
            $resp = Response::HTTP_FORBIDDEN;
        }
        return $resp;
    }

    /**
     * unlock folder
     * @author Olonash
     * @param Folder $folder
     * @param User $user
     * @return bool
     */
    public function unlockFolder (Folder $folder, User $user)
    {
        $resp = Response::HTTP_OK;
        $_folder = $this->repository->findFolderLockableByUser($folder, $user);
        if ($_folder ) {
            if($folder->getLocked() == Constant::LOCKED) {
                $folder->setLocked(Constant::NOT_LOCKED);
                $this->saveAndFlush($folder);
            } else {
                $resp = Response::HTTP_ACCEPTED;
            }
        } else {
            $resp = Response::HTTP_FORBIDDEN;
        }
        return $resp;
    }
}
