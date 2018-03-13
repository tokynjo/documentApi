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

    /**
     * to call on lock folder
     * @param FolderEvent $folderEvent
     */
    public function onLockedFolder(FolderEvent $folderEvent)
    {
        $folderLog = new FolderLog();
        $logAction = $this->em->getRepository(FolderLogAction::class)->find(Constant::FOLDER_LOG_ACTION_LOCKED);
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

    /**
     * to do on unlock folder
     * @param FolderEvent $folderEvent
     */
    public function onUnlockedFolder(FolderEvent $folderEvent)
    {
        $folderLog = new FolderLog();
        $logAction = $this->em->getRepository(FolderLogAction::class)->find(Constant::FOLDER_LOG_ACTION_NOT_UNLOCKED);
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

    /**
     * to execute on folder creation
     * @param FolderEvent $folderEvent
     */
    public function onCreateFolder(FolderEvent $folderEvent)
    {

        $folderLog = new FolderLog();
        $logAction = $this->em->getRepository(FolderLogAction::class)->find(Constant::FOLDER_LOG_ACTION_CREATE);
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

    /**
     * to execute on rename folder
     * @param FolderEvent $folderEvent
     */
    public function onRenameFolder(FolderEvent $folderEvent)
    {

        $folderLog = new FolderLog();
        $logAction = $this->em->getRepository(FolderLogAction::class)->find(Constant::FOLDER_LOG_ACTION_RENAME);
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

    /**
     * to execute on delete folder
     * @param FolderEvent $folderEvent
     */
    public function onDeleteFolder(FolderEvent $folderEvent)
    {

        $folderLog = new FolderLog();
        $logAction = $this->em->getRepository(FolderLogAction::class)->find(Constant::FOLDER_LOG_ACTION_DELETE);
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

    /**
     * @param FolderEvent $folderEvent
     */
    public function onChangeFolderOwner(FolderEvent $folderEvent)
    {
        $folderLog = new FolderLog();
        $logAction = $this->em->getRepository(FolderLogAction::class)->find(Constant::FOLDER_LOG_ACTION_CHANGE_OWNER);
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


    /**
     * @param FolderEvent $folderEvent
     */
    public function onCryptFolder(FolderEvent $folderEvent)
    {
        $folderLog = new FolderLog();
        $logAction = $this->em->getRepository(FolderLogAction::class)->find(Constant::FOLDER_LOG_ACTION_CRYPT);
        $folderLog->setClient($this->container->get('security.token_storage')->getToken()->getUser()->getClient())
            ->setFolder($folderEvent->getFolder())
            ->setFolderLogAction($logAction)
            ->setUser($this->container->get('security.token_storage')->getToken()->getUser())
            ->setReferer(null)
            ->setIp(getenv('SERVER_ADDR'))
            ->setUserAgent(null)
            ->setCreatedAt(new \DateTime());
        $this->folderLogManager->saveAndFlush($folderLog);
    }
    /**
     * @param FolderEvent $folderEvent
     */
    public function onDeCryptFolder(FolderEvent $folderEvent)
    {
        $folderLog = new FolderLog();
        $logAction = $this->em->getRepository(FolderLogAction::class)->find(Constant::FOLDER_LOG_ACTION_NOT_CRYPT);
        $folderLog->setClient($this->container->get('security.token_storage')->getToken()->getUser()->getClient())
            ->setFolder($folderEvent->getFolder())
            ->setFolderLogAction($logAction)
            ->setUser($this->container->get('security.token_storage')->getToken()->getUser())
            ->setReferer(null)
            ->setIp(getenv('SERVER_ADDR'))
            ->setUserAgent(null)
            ->setCreatedAt(new \DateTime());
        $this->folderLogManager->saveAndFlush($folderLog);
    }

}
