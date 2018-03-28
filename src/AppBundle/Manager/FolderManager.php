<?php
namespace AppBundle\Manager;

use ApiBundle\Entity\User;
use AppBundle\Entity\Api\ApiResponse;
use AppBundle\Entity\Constants\Constant;
use AppBundle\Entity\Folder;
use AppBundle\Entity\News;
use AppBundle\Entity\NewsType;
use AppBundle\Event\FileEvent;
use AppBundle\Event\FolderEvent;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\FolderUser;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;

class FolderManager extends BaseManager
{
    const SERVICE_NAME = 'app.folder_manager';

    protected $dispatcher = null;
    protected $tokenStorage = null;
    protected $fileManager = null;
    protected $translator = null;


    /**
     * FolderManager constructor.
     * @param EntityManagerInterface   $entityManager
     * @param type                     $class
     * @param EventDispatcherInterface $eventDispatcher
     * @param TokenStorageInterface    $tokenStorage
     * @param FileManager              $fileManager
     * @param TranslatorInterface      $translator
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        $class,
        EventDispatcherInterface $eventDispatcher,
        TokenStorageInterface $tokenStorage,
        FileManager $fileManager,
        TranslatorInterface $translator
    ) {
        parent::__construct($entityManager, $class);
        $this->dispatcher = $eventDispatcher;
        $this->tokenStorage = $tokenStorage;
        $this->fileManager = $fileManager;
        $this->translator = $translator;
    }

    /**
     * @param $user
     * @param null $id_folder
     * @return mixed
     */
    public function getStructure($user, $id_folder = null, $keyCrypt = null)
    {
        if ($id_folder === null) {
            $data["interne"]["folders"] = $this->repository->getFolderByUser($user);
            $data["externe"]["folders"] = $this->repository->getFolderInvitRequest($user);
        } else {
            $data = $this->repository->getFolderByUserIdFolder($user, $id_folder, $keyCrypt);
        }

        return $data;
    }

    /**
     * get full structure of internal folder
     * recursive folders only. Without file list
     *
     * @param  $user
     * @param  null    $id_folder
     * @param  boolean $external
     * @return ApiResponse
     */
    public function getInternalStructure($user, $id_folder = null, $external = false)
    {
        $resp = new ApiResponse();
        if (!$id_folder) { //internal and external
            //internal folders
            $folders =  $this->findBy(
                ['parentFolder'=>null, 'user' => $user, 'locked' => 0, 'deletedAt'=>null]
            );
            foreach ($folders as $folder) {
                $data[] = $this->getFolderFullStructure($folder);
            }
            if($external) {
                //external folders
                $externalsFolders = $this->repository->getFolderInvitRequest($user);
                foreach($externalsFolders as $f) {
                    $folderExternal = $this->find($f['id_folder']);
                    if(!in_array($folderExternal->getStatus(), [Constant::FOLDER_STATUS_DELETED])
                        && $folderExternal->getLocked() <> Constant::LOCKED
                    ) {
                        $data[] = $this->getFolderFullStructure($folderExternal);
                    }
                }
            }

            $resp->setCode(Response::HTTP_OK)
                ->setMessage($this->translator->trans("api.messages.success"))
                ->setData($data);

        } else {
            $folder = $this->find($id_folder);
            if ($folder) {
                $right = $this->repository->getRightToFolder($id_folder, $user);
                if ($right == Constant::RIGHT_OWNER) {
                    $data = $this->getFolderFullStructure($folder);
                    $resp->setCode(Response::HTTP_OK)
                        ->setMessage($this->translator->trans("api.messages.success"))
                        ->setData($data);
                } else {
                    $resp->setCode(Response::HTTP_FORBIDDEN)
                        ->setMessage($this->translator->trans("api.messages.lock.no_permission_to_this_folder"));
                }
            } else {
                $resp->setCode(Response::HTTP_NOT_FOUND)
                    ->setMessage($this->translator->trans("api.messages.lock.folder_not_found"));
            }
        }

        return $resp;
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

    /**
     * lock folder
     *
     * @author Olonash
     * @param  Folder $folder
     * @param  User   $user
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
                $resp->setCode(Response::HTTP_ACCEPTED)
                    ->setMessage($this->translator->trans("api.messages.lock.folder_already_locked"));

            }
        } else {
            $resp->setCode(Response::HTTP_FORBIDDEN);
            $resp->setMessage('')
                ->setMessage($this->translator->trans("api.messages.lock.no_permission_to_this_folder"));

        }

        return $resp;
    }

    /**
     * unlock folder
     *
     * @author Olonash
     * @param  Folder $folder
     * @param  User   $user
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
                $resp->setCode(Response::HTTP_ACCEPTED)
                    ->setMessage($this->translator->trans("api.messages.lock.folder_already_unlocked"));
            }
        } else {
            $resp->setCode(Response::HTTP_FORBIDDEN)
                ->setMessage($this->translator->trans("api.messages.lock.no_permission_to_this_folder"));
        }

        return $resp;
    }

    /**
     * check if the given folder name is available in the folder parent <br>
     * To ensure the that the folder name is unique <br>
     * Return false if already exists
     *
     * @param  $folderParentId
     * @param  $name
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
     *
     * @param  $folderParentId
     * @param  $name
     * @param  $user
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
     *
     * @param  $folderId
     * @param  $user
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
                ]
            )
            ) {
                $hasRight = true;
            }
        }

        return $hasRight;
    }

    /**
     * Rename folder
     *
     * @param  Folder $folder
     * @param  $name
     * @return mixed
     */
    public function renameFolder(Folder $folder, $name, $user)
    {
        $resp = new ApiResponse();
        if (!$this->hasRightToCreateFolder($folder->getId(), $user)) {
            $resp->setCode(Response::HTTP_FORBIDDEN)
                ->setMessage($this->translator->trans("api.messages.lock.no_permission_to_this_folder"));
            return $resp;
        }
        $parentFolderId = $folder->getParentFolder() ? $folder->getParentFolder()->getId() : null;
        if (!$this->isFolderNameAvailable($parentFolderId, $name)) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)
                ->setMessage($this->translator->trans("api.messages.rename.folder_name_already_exists"));
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
                ->setMessage($this->translator->trans("api.messages.lock.folder_not_found"));
            return $resp;
        }
        if (!$this->hasRightToCreateFolder($folder_id, $this->tokenStorage->getToken()->getUser())) {
            $resp->setCode(Response::HTTP_FORBIDDEN)
                ->setMessage($this->translator->trans("api.messages.lock.no_permission_to_this_folder"));
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
     *
     * @param  $folderId
     * @param  $user
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
                ]
            )
            ) {
                $hasRight = true;
            }
        }

        return $hasRight;
    }

    /**
     * get users assigned to a folder
     *
     * @param  $folder_id
     * @return mixed
     */
    public function getUsersToFolder($folder_id)
    {
        $users = $this->repository->getUsersToFolder($folder_id);

        return $users;
    }

    /**
     * recursively setting folder owner and there children files owner
     *
     * @param  Folder $folder
     * @param  User   $user
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
     *
     * @param  $id
     * @return mixed
     */
    public function getPelmalink($id)
    {
        return $this->repository->getPermalink($id);
    }

    /**
     * encrypt folder and create log
     *
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
     *
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


    /**
     * To move data
     *
     * @param  $parent_id
     * @param  null      $folder_ids
     * @param  null      $file_ids
     * @return ApiResponse
     */
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
                    ->setMessage($this->translator->trans("api.messages.move_data.destination_not_found"));

                return $resp;
            }
        }
        if (!$folder_ids && !$file_ids) {
            $resp
                ->setCode(Response::HTTP_BAD_REQUEST)
                ->setMessage($this->translator->trans("api.messages.move_data.at_least_on_parameters_is_mandatory"));

            return $resp;
        } else {
            $this->entityManager->getConnection()->beginTransaction();
            //move folder
            $movedFolders = [];
            if ($folder_ids) {
                $folders = new \ArrayIterator(explode(',', $folder_ids));
                while ($folders->valid()) {
                    if (!$this->hasRightToCreateFolder($folders->current(), $this->tokenStorage->getToken()->getUser())) {
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
                    if (!$this->fileManager                    ->hasRightToMoveFile($files->current(), $this->tokenStorage->getToken()->getUser())
                    ) {
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
                $resp->setMessage($this->translator->trans("api.messages.move_data.do_not_have_permission"))
                    ->setCode(Response::HTTP_FORBIDDEN);
                $data['folders'] = $folders_no_right;
                $data['files'] = $files_no_right;
            } else {
                $this->entityManager->commit();
                $data['folders'] = $movedFolders;
                $data['files'] = $movedFiles;
                $resp->setMessage($this->translator->trans("api.messages.success"));
            }
            $resp->setData($data);

            return $resp;
        }
    }

    /**
     * To copy data
     *
     * @param  Folder $recipient
     * @param  string $idsfolders
     * @param  string $idsFiles
     * @param  User   $user
     * @return array
     */
    public function copyData($recipient, $idsfolders, $idsFiles, User $user)
    {
        $data["file_copied"] = [];
        if ($idsfolders) {
            $idsFolders = array_unique(preg_split("/(;|,)/", $idsfolders));
            $folders = $this->repository->getByIds($idsFolders);
            foreach ($folders as $folder) {
                if (!$this->hasRightToCreateFolder($folder->getId(), $user)) {
                    $copyfolder = clone $folder;
                    $copyfolder->setUser($user);
                    $copyfolder->setUser($recipient->getUser());
                    $copyfolder->setCreatedBy($user);
                    $copyfolder->setParentFolder($recipient);
                    $this->entityManager->detach($copyfolder);
                    $this->saveAndFlush($copyfolder);
                    $data["folder_copied"][$folder->getId()] = $folder->getName();
                    $folderEvent = new FolderEvent($folder);
                    $this->dispatcher->dispatch($folderEvent::FOLDER_ON_COPY, $folderEvent);
                    $this->copyAllFolder($folder, $copyfolder, $user, $data);
                    $this->copyFilesInFolder($folder->getFiles(), $recipient, $user, $data);
                }
            }
        }
        if ($idsFiles) {
            $idsFiles = array_unique(preg_split("/(;|,)/", $idsFiles));
            $files = $this->entityManager->getRepository("AppBundle:File")->getByIds($idsFiles);
            $this->copyFilesInFolder($files, $recipient, $user, $data);
        }
        return $data;
    }

    /**
     * move one folder
     *
     * @param  Folder $parent_folder
     * @param  Folder $folder
     * @return bool
     */
    public function moveFolder(Folder $parent_folder = null, Folder $folder)
    {
        $resp = false;
        if ($folder) {
            $folder->setParentFolder($parent_folder);
            $this->saveAndFlush($folder);
            //save log
            $folderEvent = new FolderEvent($folder);
            $this->dispatcher->dispatch($folderEvent::FOLDER_ON_MOVE, $folderEvent);
            $resp = true;
        }

        return $resp;
    }

    /*
     * @param $dossier
     * @param $destinataire
     */
    public function copyAllFolder($dossier, $destinataire, $user, &$data)
    {
        foreach ($dossier->getChildFolders() as $child) {
            $copyfolder = clone $child;
            $copyfolder->setParentFolder($destinataire);
            $copyfolder->setUser($user);
            $copyfolder->setUser($destinataire->getUser());
            $copyfolder->setCreatedBy($user);
            $this->entityManager->detach($copyfolder);
            $this->saveAndFlush($copyfolder);
            $this->copyFilesInFolder($child->getFiles(), $copyfolder, $user, $data);
            $data["folder_copied"][$child->getId()] = $child->getName();
            $folderEvent = new FolderEvent($copyfolder);
            $this->dispatcher->dispatch($folderEvent::FOLDER_ON_COPY, $folderEvent);
            $this->copyAllFolder($child, $copyfolder, $user, $data);
        }
    }

    /**
     * Copy file in a folder
     *
     * @param $files
     * @param $recipient
     * @param $user
     * @param $data
     */
    public function copyFilesInFolder($files, $recipient, $user, &$data)
    {
        foreach ($files as $file) {
            if (!$this->fileManager->hasRightToMoveFile($file->getId(), $user)
            ) {
                $copyFile = clone $file;
                $copyFile->setFolder($recipient);
                $copyFile->setUser($recipient->getUser());
                $this->entityManager->detach($copyFile);
                $this->fileManager->saveAndFlush($copyFile);
                $data["file_copied"][$file->getId()] = $file->getName();
                $fileEvent = new FileEvent($copyFile);
                $this->dispatcher->dispatch($fileEvent::FILE_ON_COPY, $fileEvent);
            }
        }
    }


    /**
     * get full structure of folder content.
     * Folders only
     *
     * @param  Folder $folder
     * @return mixed
     */
    public function getFolderFullStructure(Folder $folder )
    {
        $data['id'] = $folder->getId();
        $data['name'] = $folder->getName();
        $data['project_id'] = $folder->getProjectId();
        $data['description'] = $folder->getDescription();
        $data['external'] = ($folder->getShare() == Constant::SHARED) ? true : false;
        $data['children'] = [];
        $subFolders = $folder->getChildFolders();
        $iterator = $subFolders->getIterator();
        while ($iterator->valid()) {
            $data['children'][] = $this->getFolderFullStructure($iterator->current());
            $iterator->next();
        }
        
        return $data;
    }

    /***
     * @param $nbFolder
     * @param $taille
     * @param $dossier
     * @param $nbFiles
     */
    public function recurssive(&$nbFolder, &$taille, $dossier, &$nbFiles)
    {
        if($dossier->getChildFolders()) {
            foreach ($dossier->getChildFolders() as $child) {
                $fileManager = $this->fileManager;
                $dataFile = $fileManager->getTailleTotal($child->getId());
                if ($dataFile) {
                    $taille = $taille + $dataFile["size"];
                    $nbFiles += $dataFile["nb_file"];
                }
                $nbFolder++;
                $this->recurssive($nbFolder, $taille, $child, $nbFiles);
            }
        }
    }
}
