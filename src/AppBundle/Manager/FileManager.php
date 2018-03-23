<?php

namespace AppBundle\Manager;

use ApiBundle\Entity\User;
use AppBundle\Entity\Api\ApiResponse;
use AppBundle\Entity\Constants\Constant;
use AppBundle\Entity\File;
use AppBundle\Entity\Folder;
use AppBundle\Event\FileEvent;
use AppBundle\Event\FolderEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class FileManager extends BaseManager
{
    const SERVICE_NAME = 'app.file_manager';

    protected $dispatcher = null;
    protected $tokenStorage = null;

    /**
     * FileManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param $class
     * @param EventDispatcherInterface $eventDispatcher
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        $class,
        EventDispatcherInterface $eventDispatcher,
        TokenStorageInterface $tokenStorage
    ) {
        parent::__construct($entityManager, $class);
        $this->tokenStorage = $tokenStorage;
        $this->dispatcher = $eventDispatcher;
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
        return $this->repository->getFilesInvitRequest($user, $id_folder,$keyCrypt);
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
}
