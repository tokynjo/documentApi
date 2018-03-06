<?php
namespace AppBundle\Manager;

use ApiBundle\Entity\User;
use AppBundle\Entity\Constants\Constant;
use AppBundle\Entity\Folder;
use AppBundle\Event\FolderEvent;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\EventDispatcher\EventDispatcherInterface;

class FileLogManager extends BaseManager
{
    const SERVICE_NAME = 'app.file_log_manager';

    /**
     * @param EntityManagerInterface $entityManager
     * @param $class
     */
    public function __construct(EntityManagerInterface $entityManager, $class)
    {
        parent::__construct($entityManager, $class);
    }
}
