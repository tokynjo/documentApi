<?php
/**
 * Created by PhpStorm.
 * User: Nasolo RANDIANINA
 * Date: 27/02/2018
 * Time: 16:31
 */
namespace AppBundle\EventListener;

use AppBundle\Entity\Client;
use AppBundle\Entity\Constants\Constant;
use AppBundle\Entity\FolderLog;
use AppBundle\Entity\FolderLogAction;
use AppBundle\Event\FolderEvent;
use AppBundle\Manager\FolderLogManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Folder event Listener
 * Listens folder event
 * @package AppBundle\EventListener
 */
class FolderListener
{
    private $folderLogManager;
    private $container;
    private $em;

    public function __construct(FolderLogManager $folderLogManager, ContainerInterface $container)
    {
        $this->folderLogManager = $folderLogManager;
        $this->container = $container;
        $this->em = $this->container->get('doctrine.orm.entity_manager');
    }

    public function onLockedFolder(FolderEvent $folderEvent)
    {
        $folderLog = new FolderLog();
        $logAction = $this->em->getRepository(FolderLogAction::class)->find(Constant::FOLDER_LOG_ACTION_NOT_SHARE);
        $folderLog->setClient($this->container->get('security.token_storage')->getToken()->getUser()->getClient())
            ->setFolder($folderEvent->getFolder())
            ->setFolderLogAction($logAction)
            ->setUser($this->container->get('security.token_storage')->getToken()->getUser())
            ->setReferer(null)
            ->setIp(null)
            ->setUserAgent(null)
            ->setCreatedAt(new \DateTime());

        $this->folderLogManager->saveAndFlush($folderLog);
    }
    public function onUnlockedFolder(FolderEvent $folderEvent)
    {
        $folderLog = new FolderLog();
        $logAction = $this->em->getRepository(FolderLogAction::class)->find(Constant::FOLDER_LOG_ACTION_SHARE);
        $folderLog->setClient($this->container->get('security.token_storage')->getToken()->getUser()->getClient())
            ->setFolder($folderEvent->getFolder())
            ->setFolderLogAction($logAction)
            ->setUser($this->container->get('security.token_storage')->getToken()->getUser())
            ->setReferer(null)
            ->setIp(null)
            ->setUserAgent(null)
            ->setCreatedAt(new \DateTime());

        $this->folderLogManager->saveAndFlush($folderLog);
    }
}
