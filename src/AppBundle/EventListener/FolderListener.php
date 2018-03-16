<?php
/**
 * Created by PhpStorm.
 * User: Nasolo RANDIANINA
 * Date: 27/02/2018
 * Time: 16:31
 */
namespace AppBundle\EventListener;

use AppBundle\Entity\Constants\Constant;
use AppBundle\Entity\FolderLog;
use AppBundle\Entity\FolderLogAction;
use AppBundle\Event\FolderEvent;
use AppBundle\Manager\FolderLogManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Folder event Listener
 * Listens folder event
 *
 * @package AppBundle\EventListener
 */
class FolderListener
{
    private $folderLogManager;
    private $em;
    private $tokenStorage;
    private $translator;

    
    public function __construct(
        FolderLogManager $folderLogManager,
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        TranslatorInterface $translator
    ) {
        $this->folderLogManager = $folderLogManager;
        $this->em = $entityManager;
        $this->tokenStorage = $tokenStorage;
        $this->translator = $translator;
    }

    /**
     * to call on lock folder
     *
     * @param FolderEvent $folderEvent
     */
    public function onLockedFolder(FolderEvent $folderEvent)
    {
        $folderLog = new FolderLog();
        $logAction = $this->em->getRepository(FolderLogAction::class)->find(Constant::FOLDER_LOG_ACTION_LOCKED);
        $folderLog->setClient($this->tokenStorage->getToken()->getUser()->getClient())
            ->setFolder($folderEvent->getFolder())
            ->setFolderLogAction($logAction)
            ->setUser($this->tokenStorage->getToken()->getUser())
            ->setReferer(null)
            ->setIp(null)
            ->setUserAgent(null)
            ->setCreatedAt(new \DateTime());

        $this->folderLogManager->saveAndFlush($folderLog);
    }

    /**
     * to do on unlock folder
     *
     * @param FolderEvent $folderEvent
     */
    public function onUnlockedFolder(FolderEvent $folderEvent)
    {
        $folderLog = new FolderLog();
        $logAction = $this->em->getRepository(FolderLogAction::class)->find(Constant::FOLDER_LOG_ACTION_NOT_UNLOCKED);
        $folderLog->setClient($this->tokenStorage->getToken()->getUser()->getClient())
            ->setFolder($folderEvent->getFolder())
            ->setFolderLogAction($logAction)
            ->setUser($this->tokenStorage->getToken()->getUser())
            ->setReferer(null)
            ->setIp(null)
            ->setUserAgent(null)
            ->setCreatedAt(new \DateTime());

        $this->folderLogManager->saveAndFlush($folderLog);
    }

    /**
     * to execute on folder creation
     *
     * @param FolderEvent $folderEvent
     */
    public function onCreateFolder(FolderEvent $folderEvent)
    {

        $folderLog = new FolderLog();
        $logAction = $this->em->getRepository(FolderLogAction::class)->find(Constant::FOLDER_LOG_ACTION_CREATE);
        $folderLog->setClient($this->tokenStorage->getToken()->getUser()->getClient())
            ->setFolder($folderEvent->getFolder())
            ->setFolderLogAction($logAction)
            ->setUser($this->tokenStorage->getToken()->getUser())
            ->setReferer(null)
            ->setIp(null)
            ->setUserAgent(null)
            ->setCreatedAt(new \DateTime());

        $this->folderLogManager->saveAndFlush($folderLog);
    }

    /**
     * to execute on rename folder
     *
     * @param FolderEvent $folderEvent
     */
    public function onRenameFolder(FolderEvent $folderEvent)
    {

        $folderLog = new FolderLog();
        $logAction = $this->em->getRepository(FolderLogAction::class)->find(Constant::FOLDER_LOG_ACTION_RENAME);
        $folderLog->setClient($this->tokenStorage->getToken()->getUser()->getClient())
            ->setFolder($folderEvent->getFolder())
            ->setFolderLogAction($logAction)
            ->setUser($this->tokenStorage->getToken()->getUser())
            ->setReferer(null)
            ->setIp(null)
            ->setUserAgent(null)
            ->setCreatedAt(new \DateTime());

        $this->folderLogManager->saveAndFlush($folderLog);
    }

    /**
     * to execute on delete folder
     *
     * @param FolderEvent $folderEvent
     */
    public function onDeleteFolder(FolderEvent $folderEvent)
    {

        $folderLog = new FolderLog();
        $logAction = $this->em->getRepository(FolderLogAction::class)->find(Constant::FOLDER_LOG_ACTION_DELETE);
        $folderLog->setClient($this->tokenStorage->getToken()->getUser()->getClient())
            ->setFolder($folderEvent->getFolder())
            ->setFolderLogAction($logAction)
            ->setUser($this->tokenStorage->getToken()->getUser())
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
        $folderLog->setClient($this->tokenStorage->getToken()->getUser()->getClient())
            ->setFolder($folderEvent->getFolder())
            ->setFolderLogAction($logAction)
            ->setUser($this->tokenStorage->getToken()->getUser())
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
        $folderLog->setClient($this->tokenStorage->getToken()->getUser()->getClient())
            ->setFolder($folderEvent->getFolder())
            ->setFolderLogAction($logAction)
            ->setUser($this->tokenStorage->getToken()->getUser())
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
        $folderLog->setClient($this->tokenStorage->getToken()->getUser()->getClient())
            ->setFolder($folderEvent->getFolder())
            ->setFolderLogAction($logAction)
            ->setUser($this->tokenStorage->getToken()->getUser())
            ->setReferer(null)
            ->setIp(getenv('SERVER_ADDR'))
            ->setUserAgent(null)
            ->setCreatedAt(new \DateTime());
        $this->folderLogManager->saveAndFlush($folderLog);
    }

    /**
     * to execute on move folder
     *
     * @param FolderEvent $folderEvent
     */
    public function onMoveFolder(FolderEvent $folderEvent)
    {
        $folderLog = new FolderLog();
        $logAction = $this->em->getRepository(FolderLogAction::class)->find(Constant::FOLDER_LOG_ACTION_MOVE);
        $folderLog->setClient($this->tokenStorage->getToken()->getUser()->getClient())
            ->setFolder($folderEvent->getFolder())
            ->setFolderLogAction($logAction)
            ->setUser($this->tokenStorage->getToken()->getUser())
            ->setReferer(null)
            ->setIp(null)
            ->setUserAgent(null);

        $this->folderLogManager->saveAndFlush($folderLog);
    }

    /**
     * @param FolderEvent $folderEvent
     */
    public function onCopyFolder(FolderEvent $folderEvent)
    {
        $folderLog = new FolderLog();
        $logAction = $this->em->getRepository(FolderLogAction::class)->find(Constant::FOLDER_LOG_ACTION_COPY);
        $folderLog->setClient($this->tokenStorage->getToken()->getUser()->getClient())
            ->setFolder($folderEvent->getFolder())
            ->setFolderLogAction($logAction)
            ->setUser($this->tokenStorage->getToken()->getUser())
            ->setReferer(null)
            ->setIp(getenv('SERVER_ADDR'))
            ->setUserAgent(null)
            ->setCreatedAt(new \DateTime());
        $this->folderLogManager->saveAndFlush($folderLog);
    }
}
