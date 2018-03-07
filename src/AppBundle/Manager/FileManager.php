<?php

namespace AppBundle\Manager;

use ApiBundle\Entity\User;
use AppBundle\Entity\Constants\Constant;
use AppBundle\Entity\File;
use AppBundle\Event\FileEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FileManager extends BaseManager
{
    const SERVICE_NAME = 'app.file_manager';

    protected $container = null;

    public function __construct(EntityManagerInterface $entityManager, $class, ContainerInterface $container)
    {
        parent::__construct($entityManager, $class);
        $this->container = $container;
    }

    /**
     * @param $user
     * @param $id_folder
     * @return mixed
     */
    public function getStructureInterne($user, $id_folder = null)
    {
        if ($id_folder == null) {
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
    public function setFileOwner (File $file, User $user) {
        $file
            ->setUpdatedAt(new \DateTime())
            ->setUser($user);
        $this->saveAndFlush($file);
        //save folder delete event log
        $fileEvent = new FileEvent($file);
        $this->container->get('event_dispatcher')->dispatch($fileEvent::FILE_ON_CHANGE_OWNER, $fileEvent);

        return true;
    }
}
