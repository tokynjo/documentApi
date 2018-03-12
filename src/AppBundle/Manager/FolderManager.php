<?php
namespace AppBundle\Manager;

use ApiBundle\Entity\User;
use AppBundle\Entity\Api\ApiResponse;
use AppBundle\Entity\Constants\Constant;
use AppBundle\Entity\Folder;
use AppBundle\Entity\News;
use AppBundle\Entity\NewsType;
use AppBundle\Event\FolderEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class FolderManager extends BaseManager
{
    const SERVICE_NAME = 'app.folder_manager';

    protected $dispatcher = null;
    protected $tokenStorage = null;
    protected $fileManager = null;



    public function __construct(
        EntityManagerInterface $entityManager,
        $class,
        EventDispatcher $eventDispatcher,
        TokenStorageInterface $tokenStorage,
        FileManager $fileManager

    )
    {
        parent::__construct($entityManager, $class);
        $this->dispatcher = $eventDispatcher;
        $this->tokenStorage = $tokenStorage;
        $this->fileManager = $fileManager;
    }

    /**
     * @param $user
     * @param null $id_folder
     * @return mixed
     */
    public function getStructure($user, $id_folder = null)
    {
        if ($id_folder === null) {
            $data["interne"]["folders"] = $this->repository->getFolderByUser($user);
            $data["externe"]["folders"] = $this->repository->getFolderInvitRequest($user);
        } else {
            $data["interne"]["folders"] = $this->repository->getFolderByUserIdFolder($user, $id_folder);
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
     * @return ApiResponse
     */
    public function lockFolder(Folder $folder, User $user)
    {
        $resp = new ApiResponse();
        $_folder = $this->repository->findFolderLockableByUser($folder, $user);
        if ($_folder) {
            if ($folder->getLocked() == Constant::NOT_LOCKED) {
                $folder->setLocked(Constant::LOCKED);
                $this->saveAndFlush($folder);
                //save log
                $folderEvent = new FolderEvent($folder);
                $this->dispatcher->dispatch($folderEvent::FOLDER_ON_LOCK, $folderEvent);
                $resp->setCode(Response::HTTP_OK);
            } else {
                $resp->setCode(Response::HTTP_ACCEPTED) ;
                $resp->setMessage('Folder already locked');
            }
        } else {
            $resp->setCode(Response::HTTP_FORBIDDEN);
            $resp->setMessage('Do not have permission to this folder');
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
    public function unlockFolder(Folder $folder, User $user)
    {
        $resp = Response::HTTP_OK;
        $_folder = $this->repository->findFolderLockableByUser($folder, $user);
        if ($_folder) {
            if ($folder->getLocked() == Constant::LOCKED) {
                $folder->setLocked(Constant::NOT_LOCKED);
                $this->saveAndFlush($folder);
                //save log
                $folderEvent = new FolderEvent($folder);
                $this->dispatcher->dispatch($folderEvent::FOLDER_ON_UNLOCK, $folderEvent);
                $resp->setCode(Response::HTTP_OK);
            } else {
                $resp->setCode(Response::HTTP_ACCEPTED) ;
                $resp->setMessage('Folder already unlocked');
            }
        } else {
            $resp->setCode(Response::HTTP_FORBIDDEN);
            $resp->setMessage('Do not have permission to this folder');
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

        foreach ($_folders as $folder) {
            if (strtolower($folder->getName()) == strtolower($name)) {
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
        $parent = $parent ? $parent : null;
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

        //saving actuality
        $news = new News();
        $newsTypeRepo = $this->entityManager->find(NewsType::class, Constant::NEWS_TYPE_CREATE_FOLDER);
        $news->setFolder($folder)
            ->setUser($user)
            ->setParent(null)
            ->setType($newsTypeRepo)
            ->setData([]);
        $this->saveAndFlush($news);
        //save log
        $folderEvent = new FolderEvent($folder);
        $this->dispatcher->dispatch($folderEvent::FOLDER_ON_CREATION, $folderEvent);

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
        if (!$folderId) {
            $hasRight = true;
        } else {
            $right = $this->repository->getRightToFolder($folderId, $user);

            if ($right && in_array(
                    $right,
                    [
                        Constant::RIGHT_OWNER,
                        Constant::RIGHT_MANAGER,
                        Constant::RIGHT_CONTRIBUTOR
                    ])
            ) {
                $hasRight = true;
            }
        }

        return $hasRight;
    }

    /**
     * Rename folder
     * @param Folder $folder
     * @param $name
     * @return mixed
     */
    public function renameFolder(Folder $folder, $name)
    {
        $folder->setName($name)
            ->setUpdatedAt(new \DateTime());

        return $this->saveAndFlush($folder);
    }

    /**
     * Rename folder
     * @param Folder $folder
     * @return mixed
     */
    public function deleteFolder(Folder $folder)
    {
        $folder->setStatus(Constant::FOLDER_STATUS_DELETED)
            ->setUpdatedAt(new \DateTime())
            ->setDeletedBy($this->tokenStorage->getToken()->getUser());
        $this->saveAndFlush($folder);
        //save folder delete event log
        $folderEvent = new FolderEvent($folder);
        $this->dispatcher->dispatch($folderEvent::FOLDER_ON_DELETE, $folderEvent);

        //set files deleted
        foreach ($folder->getFiles() as $file) {
            $this->fileManager->deleteFile($file);
        }

        foreach ($folder->getChildFolders() as $_folder) {
            $this->deleteFolder($_folder);
        }

        return $folder;
    }

    /**
     * to check if a user has right to delete folder
     * @param $folderId
     * @param $user
     * @return bool
     */
    public function hasRightToDeleteFolder($folderId, $user)
    {
        $hasRight = false;
        if (!$folderId) {
            $hasRight = true;
        } else {
            $right = $this->repository->getRightToFolder($folderId, $user);

            if ($right && in_array(
                    $right,
                    [
                        Constant::RIGHT_OWNER,
                        Constant::RIGHT_MANAGER
                    ])
            ) {
                $hasRight = true;
            }
        }

        return $hasRight;
    }

    /**
     * get users assigned to a folder
     *
     * @param $folder_id
     * @return mixed
     */
    public function getUsersToFolder($folder_id)
    {
        $users = $this->repository->getUsersToFolder($folder_id);

        return $users;
    }

    /**
     * recursively setting folder owner and there children files owner
     * @param Folder $folder
     * @param User $user
     * @return bool
     */
    public function setFolderOwner(Folder $folder, User $user)
    {
        $folder
            ->setUpdatedAt(new \DateTime())
            ->setUser($user);
        $this->saveAndFlush($folder);
        //save folder change owner event log
        $folderEvent = new FolderEvent($folder);
        $this->dispatcher->dispatch($folderEvent::FOLDER_ON_CHANGE_OWNER, $folderEvent);

        //set files owner
        foreach ($folder->getFiles() as $file) {
            $this->fileManager->setFileOwner($file, $user);
        }

        //recursively setting folders owner
        foreach ($folder->getChildFolders() as $_folder) {
            $this->setFolderOwner($_folder, $user);
        }

        return true;
    }
}
