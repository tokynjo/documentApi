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

    /**
     * check if the given folder name is available in the folder parent <br>
     * To ensure the that the folder name is unique <br>
     * Return false if already exists
     * @param $folderParentId
     * @param $name
     * @return bool
     */
    public function isFolderNameAvailable($folderParentId, $name)
    {
        $resp = true;
        $_folders = $this->repository->findDirectChildFolder($folderParentId);

        foreach($_folders as $folder) {
            if(strtolower($folder->getName()) == strtolower($name)) {
                $resp = false;
            }
        }
        return $resp;
    }

    /**
     * create a new folder to the folderParentId
     * @param $folderParentId
     * @param $name
     * @param $user
     * @return Folder
     */
    public function createFolder($folderParentId, $name, $user)
    {
        $folder = new Folder();

        $parent = $this->find($folderParentId);

        $folder->setName($name)
            ->setHash('hash')
            ->setLocked(Constant::NOT_LOCKED)
            ->setUser($user)
            ->setStatus(Constant::FOLDER_STATUS_CREATED)
            ->setParentFolder($parent)
            ->setShare(Constant::NOT_SHARED)
            ->setCrypt(Constant::NOT_CRYPTED)
            ->setCreatedAt(new \DateTime());

        $this->saveAndFlush($folder);
        $folder->setHash(sha1($folder->getId()));
        $this->saveAndFlush($folder);
        return $folder;
    }

    /**
     * to check if a user has right to create folder in a given folder
     * @param $folderId
     * @param $user
     * @return bool
     */
    public function hasRightToCreateFolder($folderId, $user)
    {
        $hasRight = false;
        $right = $this->repository->getRightToFolder($folderId, $user);
        if (in_array(
            $right,
            [
                Constant::RIGHT_OWNER,
                Constant::RIGHT_MANAGER,
                Constant::RIGHT_CONTRIBUTOR
            ])){
            $hasRight = true;
        }
        return $hasRight;
    }
}
