<?php
/**
 * Created by PhpStorm.
 * User: Nasolo RANDIANINA
 * Date: 27/02/2018
 * Time: 16:31
 */
namespace AppBundle\EventListener;

use AppBundle\Entity\Constants\Constant;
use AppBundle\Entity\FileLog;
use AppBundle\Entity\FileLogAction;
use AppBundle\Event\FileEvent;
use AppBundle\Manager\FileLogManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * File event Listener
 * Listen file event
 * @package AppBundle\EventListener
 */
class FileListener
{
    private $fileLogManager;
    private $container;
    private $em;

    /**
     * @param FileLogManager $fileLogManager
     * @param ContainerInterface $container
     */
    public function __construct(FileLogManager $fileLogManager, ContainerInterface $container)
    {
        $this->fileLogManager = $fileLogManager;
        $this->container = $container;
        $this->em = $this->container->get('doctrine.orm.entity_manager');
    }


    /**
     * to execute on delete file
     * @param FileEvent $fileEvent
     */
    public function onDeleteFile(FileEvent $fileEvent)
    {
        $fileLog = new FileLog();
        $logAction = $this->em->getRepository(FileLogAction::class)->find(Constant::FILE_LOG_ACTION_DELETE);
        $fileLog->setClient($this->container->get('security.token_storage')->getToken()->getUser()->getClient())
            ->setFile($fileEvent->getFile())
            ->setFileLogAction($logAction)
            ->setUser($this->container->get('security.token_storage')->getToken()->getUser())
            ->setReferer(null)
            ->setIp(null)
            ->setUserAgent(null)
            ->setCreatedAt(new \DateTime());

        $this->fileLogManager->saveAndFlush($fileLog);
    }

    /**
     * To execute on changing file owner
     * @param FileEvent $fileEvent
     */
    public function onChangeFileOwner(FileEvent $fileEvent)
    {
        $fileLog = new FileLog();
        $logAction = $this->em->getRepository(FileLogAction::class)->find(Constant::FILE_LOG_ACTION_CHANGE_OWNER);
        $fileLog->setClient($this->container->get('security.token_storage')->getToken()->getUser()->getClient())
            ->setFile($fileEvent->getFile())
            ->setFileLogAction($logAction)
            ->setUser($this->container->get('security.token_storage')->getToken()->getUser())
            ->setReferer(null)
            ->setIp(null)
            ->setUserAgent(null)
            ->setCreatedAt(new \DateTime());

        $this->fileLogManager->saveAndFlush($fileLog);
    }
}
