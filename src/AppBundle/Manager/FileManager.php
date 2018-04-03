<?php

namespace AppBundle\Manager;

use ApiBundle\Entity\User;
use AppBundle\Entity\Api\ApiResponse;
use AppBundle\Entity\Constants\Constant;
use AppBundle\Entity\File;
use AppBundle\Entity\Folder;
use AppBundle\Event\FileEvent;
use AppBundle\Event\FolderEvent;
use AppBundle\Services\OpenStack\ObjectStore;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\View\View;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Unirest\Exception;

class FileManager extends BaseManager
{
    const SERVICE_NAME = 'app.file_manager';

    protected $dispatcher = null;
    protected $tokenStorage = null;
    protected $objectStore = null;


    /**
     * FileManager constructor.
     *
     * @param EntityManagerInterface   $entityManager
     * @param $class
     * @param EventDispatcherInterface $eventDispatcher
     * @param TokenStorageInterface    $tokenStorage
     * @param ObjectStore $objectStore
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        $class,
        EventDispatcherInterface $eventDispatcher,
        TokenStorageInterface $tokenStorage,
        ObjectStore $objectStore
    ) {
        parent::__construct($entityManager, $class);
        $this->tokenStorage = $tokenStorage;
        $this->dispatcher = $eventDispatcher;
        $this->objectStore = $objectStore;
    }

    /**
     * @param User $user
     * @param null $id_folder
     * @param null $keyCrypt
     * @return mixed
     */
    public function getStructureInterne(User $user, $id_folder = null, $keyCrypt = null)
    {
        if ($id_folder === null) {
            return $this->repository->getFilesByUser($user, $id_folder);
        } else {
            return $this->repository->getFilesByIdFolder($user, $id_folder, $keyCrypt);
        }
    }

    /**
     * @param $user
     * @return mixed
     */
    public function getStructureExterne($user, $id_folder = null, $keyCrypt = null)
    {
        return $this->repository->getFilesInvitRequest($user, $id_folder, $keyCrypt);
    }

    /**
     * get taille total in folder_id
     *
     * @param  $idFolder
     * @return mixed
     */
    public function getTailleTotal($idFolder)
    {
        $totat = $this->repository->getTailleTotal($idFolder);
        return (isset($totat[0]) ? $totat[0] : 0);
    }

    /**
     * @param $id_file
     * @return int
     */
    public function getInfosUSer($id_file)
    {
        $totat = $this->repository->getInfosUser($id_file);
        return (isset($totat[0]) ? $totat[0] : 0);
    }

    /**
     * delete file
     * Setting status file to deleted then save file log event
     *
     * @param  File $file
     * @return bool
     */
    public function deleteFile(File $file)
    {
        $file->setStatus(Constant::FILE_STATUS_DELETED);
        $file->setDeletedBy($this->tokenStorage->getToken()->getUser());
        $file->setUpdatedAt(new \DateTime());
        $this->saveAndFlush($file);
        //save delete file log event
        $fileEvent = new FileEvent($file);
        $this->dispatcher->dispatch($fileEvent::FILE_ON_DELETE, $fileEvent);

        return true;
    }

    /**
     * setting file owner
     *
     * @param  File $file
     * @param  User $user
     * @return bool
     */
    public function setFileOwner(File $file, User $user)
    {
        $file
            ->setUpdatedAt(new \DateTime())
            ->setUser($user);
        $this->saveAndFlush($file);
        //save folder delete event log
        $fileEvent = new FileEvent($file);
        $this->dispatcher->dispatch($fileEvent::FILE_ON_CHANGE_OWNER, $fileEvent);

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
     * @param File $file
     * @param $name
     * @param $user
     * @return ApiResponse
     */
    public function renameFile(File $file, $name, $user)
    {
        $resp = new ApiResponse();
        if ($this->hasRightRenameFile($file->getId(), $user)) {
            $resp->setCode(Response::HTTP_FORBIDDEN)
                ->setMessage('Do not have permission to this folder');
            return $resp;
        }
        $parentFolderId = $file->getFolder() ? $file->getFolder()->getId() : null;
        if (!$this->isFileNameAvalable($parentFolderId, $name)) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)
                ->setMessage('Folder name already exists');
            return $resp;
        }
        $file->setSymbolicName($name)
            ->setUpdatedAt(new \DateTime());
        $this->saveAndFlush($file);
        //save log
        $fileEvent = new FileEvent($file);
        $this->dispatcher->dispatch($fileEvent::FILE_ON_RENAME, $fileEvent);
        $resp->setCode(Response::HTTP_OK);
        return $resp;
    }

    /**
     * @param $parentFolderId
     * @param $name
     * @return bool
     */
    public function isFileNameAvalable($parentFolderId, $name)
    {
        $resp = true;
        $files = $this->repository->findDirectChildFolder($parentFolderId);
        foreach ($files as $file) {
            if (strtolower($file->getSymbolicName()) == strtolower($name)) {
                $resp = false;
            }
        }
        return $resp;
    }

    /**
     * move one file
     * Set the file folder to the given new parent folder
     *
     * @param  Folder $parent_folder
     * @param  File   $file
     * @return bool
     */
    public function moveFile(Folder $parent_folder = null, File $file = null)
    {
        $resp = false;
        if ($file) {
            $file->setFolder($parent_folder);
            $this->saveAndFlush($file);
            //save log
            $fileEvent = new FileEvent($file);
            $this->dispatcher->dispatch($fileEvent::FILE_ON_MOVE, $fileEvent);
            $resp = true;
        }

        return $resp;
    }


    /**
     * to check if a user has right to move file
     * OWNER/MANAGER/CONTRIBUTOR
     *
     * @param  $fileId
     * @param  $user
     * @return bool
     */
    public function hasRightToMoveFile($fileId, $user)
    {
        $hasRight = false;
        if (!$fileId) {
            $hasRight = true;
        } else {
            $right = $this->repository->getRightToFile($fileId, $user);

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
     * @param $fileId
     * @param $user
     * @return bool
     */
    public function hasRightRenameFile($fileId, $user)
    {
        $hasRight = false;
        if (!$fileId) {
            $hasRight = true;
        } else {
            $right = $this->repository->getRightToFile($fileId, $user);

            if ($right && in_array(
                $right,
                [
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
     * @param $folder_id
     * @param $files
     */
    public function createFiles($folder_id = null, $files = [])
    {
        $resp = new ApiResponse();
        $folder = null;
        $user = $this->tokenStorage->getToken()->getUser();
        if ($folder_id) {
            $folder = $this->entityManager->find(Folder::class, $folder_id);
            if (!$folder) {
                $resp->setCode(Response::HTTP_NOT_FOUND)
                    ->setMessage($this->translator->trans("api.messages.lock.folder_not_found"));

                return $resp;
            }
        }
        if (!$user->getOsContainer()) {
            try {
                $container = $this->objectStore->createContainer($user);
                $user->setOsContainer($container->name);
                $this->saveAndFlush($user);
            } catch (\Exception $e) {
                //traiter l'exception
                $resp->setCode(Response::HTTP_INTERNAL_SERVER_ERROR);
                $resp->setMessage("Error sur l\'envoi, contacter votre administrateur");

                return $resp;
            }
        }
        if (sizeof($files) > 0) {
            $this->entityManager->getConnection()->beginTransaction();
            try {
                foreach($files as $file ) {
                    $_file = new File();

                    if(isset($file->overwrite_id)) {
                        $archive = $this->entityManager->find(File::class, $file->overwrite_id);
                        if ($archive) {
                            $archive->setStatus(Constant::STATUS_REPLACED);
                            $this->saveAndFlush($archive);
                        } else {
                            $this->entityManager->getConnection()->rollback();
                            $this->entityManager->close();
                            //traiter l'exception
                            $resp->setCode(Response::HTTP_NOT_FOUND);
                            $resp->setMessage("File not found");

                            return $resp;
                        }
                        $_file->setArchiveFileId($file->overwrite_id);
                    }
                    //save object file
                    $_file->setFolder($folder)
                        ->setName('')
                        ->setUser($user)
                        ->setStatus(Constant::STATUS_CREATED)
                        ->setLocked(Constant::NOT_LOCKED)
                        ->setComment('')
                        ->setSize(0)
                        ->setHash('')
                        ->setServerId(0)
                        ->setUploadIp('')
                        ->setEncryption(Constant::NOT_CRYPTED)
                        ->setIdNas(0)
                        ->setShare(Constant::NOT_SHARED)
                        ->setFavorite(0)
                        ->setSymbolicName($file->name);
                    $this->saveAndFlush($_file);

                    //create object file
                    $file->id = $_file->getId();
                    $objectFile = $this->objectStore->sendFile($user->getOsContainer(), $file);

                    //save os object information
                    $_file->setName($objectFile->name)
                        ->setOsHash($objectFile->hash)
                        ->setHash(sha1($_file->getId()));
                    $this->saveAndFlush($_file);

                    //create file listner
                    $fileEvent = new FileEvent($_file);
                    $this->dispatcher->dispatch($fileEvent::FILE_ON_CREATE, $fileEvent);
                }

                $this->entityManager->commit();

            } catch (\Exception $e) {
                var_dump($e); die;
                $this->entityManager->getConnection()->rollback();
                $this->entityManager->close();
                $resp->setCode(Response::HTTP_INTERNAL_SERVER_ERROR);
                $resp->setMessage("Error sur l\'envoi, contacter votre administrateur");
            }

        }

        return $resp;
    }
    
    /**
     * Get list user shared file
     * @param $fileId
     * @return mixed
     */
    public function getUsersToFile($fileId)
    {
        $users = $this->repository->getUsersToFile($fileId);

        return $users;
    }

    public function hasRighToDelete($fileId, $user)
    {
        $hasRight = false;
        if (!$fileId) {
            $hasRight = true;
        } else {
            $right = $this->repository->getRightToFile($fileId, $user);
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
     * @param Request $request
     * @param User    $currentUser
     * @return View
     */
    public function setOwenFileAction(Request $request,User $currentUser){
        $resp = new ApiResponse();
        $fileId = $request->get('file_id');
        $userId = $request->get('user_id');
        if (!$fileId || !$userId) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('Missing mandatory parameters.');

            return new View($resp, Response::HTTP_OK);
        }
        $file = $this->find($fileId);
        if (!$file) {
            $resp->setCode(Response::HTTP_NO_CONTENT)->setMessage('File not found.');

            return new View($resp, Response::HTTP_OK);
        }
        if (!$this->hasRighToDelete($fileId, $currentUser)) {
            $resp->setCode(Response::HTTP_FORBIDDEN)->setMessage('Do not have permission to the folder');

            return new View($resp, Response::HTTP_OK);
        }
        $user = $this->entityManager->getRepository(User::class)->find($userId);
        if (!$user) {
            $resp->setCode(Response::HTTP_NO_CONTENT)->setMessage('User not found');

            return new View($resp, Response::HTTP_OK);
        }
        $this->setFileOwner($file, $user);
        $resp->setData([]);
        return new View($resp, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return object
     */
    public function find($id)
    {
        return  $this->repository->findOneBy(
            [
                'id' => $id,
                'deletedBy' => null
            ]
        );
    }
}
