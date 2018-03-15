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
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;

class FileManager extends BaseManager
{
    const SERVICE_NAME = 'app.file_manager';

    protected $container = null;
    protected $dispatcher = null;

    public function __construct(
        EntityManagerInterface $entityManager,
        $class,
        ContainerInterface $container,
        EventDispatcherInterface $eventDispatcher)
    {
        parent::__construct($entityManager, $class);
        $this->container = $container;
        $this->dispatcher = $eventDispatcher;
    }

    /**
     * @param $user
     * @param $id_folder
     * @return mixed
     */
    public function getStructureInterne($user, $id_folder = null)
    {
        if ($id_folder === null) {
            return $this->repository->getFilesByUser($user, $id_folder);
        } else {
            return $this->repository->getFilesByIdFolder($user, $id_folder);
        }
    }

    /**
     * @param $user
     * @return mixed
     */
    public function getStructureExterne($user, $id_folder = null)
    {
        return $this->repository->getFilesInvitRequest($user, $id_folder);
    }

    /**
     * get taille total in folder_id
     * @param $idFolder
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
     * @param File $file
     * @return bool
     */
    public function deleteFile(File $file)
    {
        $file->setStatus(Constant::FILE_STATUS_DELETED);
        $file->setDeletedBy($this->container->get('security.token_storage')->getToken()->getUser());
        $file->setUpdatedAt(new \DateTime());
        $this->saveAndFlush($file);
        //save delete file log event
        $fileEvent = new FileEvent($file);
        $this->container->get('event_dispatcher')->dispatch($fileEvent::FILE_ON_DELETE, $fileEvent);

        return true;
    }

    /**
     * setting file owner
     *
     * @param File $file
     * @param User $user
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
        $this->container->get('event_dispatcher')->dispatch($fileEvent::FILE_ON_CHANGE_OWNER, $fileEvent);

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
     * @param File $file
     * @param $name
     * @param $user
     * @return ApiResponse
     */
    public function renameFile(File $file, $name, $user)
    {
        $resp = new ApiResponse();
        $tab_right = [Constant::RIGHT_MANAGER];
        if (!$this->container->get(FileUserManager::SERVICE_NAME)->getRightUser($file, $user, $tab_right)
        ) {
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
        $file->setName($name)
            ->setUpdatedAt(new \DateTime());
        $this->saveAndFlush($file);
        //save log
        $fileEvent = new FileEvent($file);
        $this->dispatcher->dispatch($fileEvent::FILE_ON_RENAME, $fileEvent);
        $resp->setCode(Response::HTTP_OK);
        return $resp;
    }

    public function isFileNameAvalable($parentFolderId, $name)
    {
        $resp = true;
        $files = $this->repository->findDirectChildFolder($parentFolderId);
        foreach ($files as $file) {
            if (strtolower($file->getName()) == strtolower($name)) {
                $resp = false;
            }
        }
        return $resp;
    }

    /**
     * move one file
     * Set the file folder to the given new parent folder
     * @param Folder $parent_folder
     * @param File $file
     * @return bool
     */
    public function moveFile (Folder $parent_folder = null, File $file = null)
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
     * @param $fileId
     * @param $user
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
                    ])
            ) {
                $hasRight = true;
            }
        }

        return $hasRight;
    }
}
