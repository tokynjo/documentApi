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
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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
        EventDispatcherInterface $eventDispatcher,
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
                $resp->setCode(Response::HTTP_ACCEPTED);
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
                $resp->setCode(Response::HTTP_ACCEPTED);
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
    public function renameFolder(Folder $folder, $name, $user)
    {
        $resp = new ApiResponse();
        if (!$this->hasRightToCreateFolder($folder->getId(), $user)) {
            $resp->setCode(Response::HTTP_FORBIDDEN)
                ->setMessage('Do not have permission to this folder');
            return $resp;
        }
        $parentFolderId = $folder->getParentFolder() ? $folder->getParentFolder()->getId() : null;
        if (!$this->isFolderNameAvailable($parentFolderId, $name)) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)
                ->setMessage('Folder name already exists');
            return $resp;
        }

        $folder->setName($name)
            ->setUpdatedAt(new \DateTime());
        $this->saveAndFlush($folder);

        //save log
        $folderEvent = new FolderEvent($folder);
        $this->dispatcher->dispatch($folderEvent::FOLDER_ON_RENAME, $folderEvent);
        $resp->setCode(Response::HTTP_OK);

        return $resp;
    }

    /**
     * @param $folder_id
     * @param $user
     * @return ApiResponse
     */
    public function deleteFolder($folder_id, $user)
    {
        $resp = new ApiResponse();
        $folder = $this->find($folder_id);
        if (!$folder) {
            $resp->setCode(Response::HTTP_NO_CONTENT)
                ->setMessage('Folder not found.');
            return $resp;
        }
        if (!$this->hasRightToCreateFolder($folder_id, $this->tokenStorage->getToken()->getUser())) {
            $resp->setCode(Response::HTTP_FORBIDDEN)
                ->setMessage('Do not have permission to the folder');
            return $resp;
        }

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
        //set child folder deleted
        foreach ($folder->getChildFolders() as $_folder) {
            $this->deleteFolder($_folder, $user);
        }
        $data = [];
        $data['folder_id'] = $folder->getId();
        $resp->setData($data);

        return $resp;
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

    /**
     * Get dataFolder with current url mapping
     * @param $id
     * @return mixed
     */
    public function getPelmalink($id)
    {
        return $this->repository->getPermalink($id);
    }

    /**
     * encrypt folder and create log
     * @param $folder
     */
    public function crypt($folder, $code)
    {
        $folder->setCrypt(Constant::CRYPTED);
        $folder->setCryptPassword($code);
        $this->saveAndFlush($folder);
        $folderEvent = new FolderEvent($folder);
        $this->dispatcher->dispatch($folderEvent::FOLDER_ON_CRYPT, $folderEvent);
    }

    /**
     * Decrypt folder and create log
     * @param $folder
     */
    public function decrypt($folder)
    {
        $folder->setCrypt(Constant::NOT_CRYPTED);
        $folder->setCryptPassword(null);
        $this->saveAndFlush($folder);
        $folderEvent = new FolderEvent($folder);
        $this->dispatcher->dispatch($folderEvent::FOLDER_ON_DECRYPT, $folderEvent);
    }

    public function moveData($parent_id, $folder_ids = null, $file_ids = null)
    {
        $resp = new ApiResponse();
        $parent = null;
        $folders_no_right = [];
        $files_no_right = [];
        if ($parent_id) {
            $parent = $this->find($parent_id);
            if (!$parent) {
                $resp
                    ->setCode(Response::HTTP_NOT_FOUND)
                    ->setMessage('Parent folder not found');

                return $resp;
            }
        }
        if (!$folder_ids && !$file_ids) {
            $resp
                ->setCode(Response::HTTP_BAD_REQUEST)
                ->setMessage('At least one of folder_ids and file_ids is mandatory');

            return $resp;
        } else {
            $this->entityManager->getConnection()->beginTransaction();
            //move folder
            $movedFolders = [];
            if ($folder_ids) {
                $folders = new \ArrayIterator(explode(',', $folder_ids));
                while ($folders->valid()) {
                    if (!$this->hasRightToCreateFolder($folders->current(),$this->tokenStorage->getToken()->getUser())){
                        $folders_no_right[] = $folders->current();
                    }
                    $folder = $this->find($folders->current());
                    if ($folder && $this->moveFolder($parent, $folder)) {
                        $movedFolders[] = $folder->getId();
                    }
                    $folders->next();
                }
            }

            //move file
            $movedFiles = [];
            if ($file_ids) {
                $files = new \ArrayIterator(explode(',', $file_ids));
                while ($files->valid()) {
                    if (!$this->fileManager->hasRightToMoveFile($files->current(), $this->tokenStorage->getToken()->getUser())){
                        $files_no_right[] = $files->current();
                    }
                    $file = $this->fileManager->find($files->current());
                    if ($file && $this->fileManager->moveFile($parent, $file)) {
                        $movedFiles[] = $file->getId();
                    }
                    $files->next();
                }
            }
            $data = [];
            if ($folders_no_right || $files_no_right) {
                $this->entityManager->getConnection()->rollback();
                $this->entityManager->close();
                $resp->setMessage('No right with some folder(s)/file(s)')
                    ->setCode(Response::HTTP_FORBIDDEN);
                $data['folders'] = $folders_no_right;
                $data['files'] = $files_no_right;
            } else {
                $this->entityManager->commit();
                $data['folders'] = $movedFolders;
                $data['files'] = $movedFiles;
            }
            $resp->setData($data);

            return $resp;
        }
    }

    /**
     * move one folder
     * @param Folder $parent_folder
     * @param Folder $folder
     * @return bool
     */
    public function moveFolder (Folder $parent_folder = null, Folder $folder)
    {
        $resp = false;
        if ($folder) {
            $folder->setParentFolder($parent_folder);
            $this->saveAndFlush($folder);
            $resp = true;
        }

        return $resp;
    }
}
