<?php
namespace AppBundle\Manager;

use ApiBundle\Entity\User;
use AppBundle\Entity\Constants\Constant;
use AppBundle\Entity\Folder;
use AppBundle\Event\FolderEvent;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\EventDispatcher\EventDispatcherInterface;

class FolderLogManager extends BaseManager
{
    const SERVICE_NAME = 'app.folder_log_manager';

    public function __construct(EntityManagerInterface $entityManager, $class)
    {
        parent::__construct($entityManager, $class);
    }

    /**
     * @param $action
     * @param $user
     * @param $folder
     * @return mixed
     */
    public function createLog($action, $user, $folder)
    {
        $class = $this->class;
        $log = new $class();
        $log->setFolder($folder);
        $log->setFolderLogAction($action);
        $log->setUser($user)
            ->setReferer(null)
            ->setIp(null)
            ->setUserAgent(null)
            ->setCreatedAt(new \DateTime());
        $this->saveAndFlush($log);
        return $log;
    }
}
