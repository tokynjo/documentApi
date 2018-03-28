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
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * File event Listener
 * Listen file event
 *
 * @package AppBundle\EventListener
 */
class FileListener
{
    private $fileLogManager;
    private $em;
    private $tokenStorage;
    private $translator;

    /**
     * @param FileLogManager         $fileLogManager
     * @param EntityManagerInterface $entityManager
     * @param TokenStorageInterface  $tokenStorage
     * @param TranslatorInterface    $translator
     */
    public function __construct(
        FileLogManager $fileLogManager,
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        TranslatorInterface $translator
    ) {
        $this->fileLogManager = $fileLogManager;
        $this->em = $entityManager;
        $this->tokenStorage = $tokenStorage;
        $this->translator = $translator;
        
        return $this;
    }


    /**
     * to execute on delete file
     *
     * @param FileEvent $fileEvent
     */
    public function onDeleteFile(FileEvent $fileEvent)
    {
        $fileLog = new FileLog();
        $logAction = $this->em->getRepository(FileLogAction::class)->find(Constant::FILE_LOG_ACTION_DELETE);
        $fileLog->setClient($this->tokenStorage->getToken()->getUser()->getClient())
            ->setFile($fileEvent->getFile())
            ->setFileLogAction($logAction)
            ->setUser($this->tokenStorage->getToken()->getUser())
            ->setReferer(null)
            ->setIp(getenv('REMOTE_ADDR'))
            ->setUserAgent(getenv('HTTP_USER_AGENT'))
            ->setCreatedAt(new \DateTime());

        $this->fileLogManager->saveAndFlush($fileLog);
    }

    /**
     * To execute on changing file owner
     *
     * @param FileEvent $fileEvent
     */
    public function onChangeFileOwner(FileEvent $fileEvent)
    {
        $fileLog = new FileLog();
        $logAction = $this->em->getRepository(FileLogAction::class)->find(Constant::FILE_LOG_ACTION_CHANGE_OWNER);
        $fileLog->setClient($this->tokenStorage->getToken()->getUser()->getClient())
            ->setFile($fileEvent->getFile())
            ->setFileLogAction($logAction)
            ->setUser($this->tokenStorage->getToken()->getUser())
            ->setReferer(null)
            ->setIp(getenv('REMOTE_ADDR'))
            ->setUserAgent($_SERVER['HTTP_USER_AGENT'])
            ->setCreatedAt(new \DateTime());

        $this->fileLogManager->saveAndFlush($fileLog);
    }

    /**
     * to execute on rename file
     *
     * @param FileEvent $fileEvent
     */
    public function onRenameFile(FileEvent $fileEvent)
    {
        $fileLog = new FileLog();
        $logAction = $this->em->getRepository(FileLogAction::class)->find(Constant::FILE_LOG_ACTION_RENAME);
        $fileLog->setClient($this->tokenStorage->getToken()->getUser()->getClient())
            ->setFile($fileEvent->getFile())
            ->setFileLogAction($logAction)
            ->setUser($this->tokenStorage->getToken()->getUser())
            ->setReferer(null)
            ->setIp(getenv('REMOTE_ADDR'))
            ->setUserAgent(getenv('HTTP_USER_AGENT'))
            ->setCreatedAt(new \DateTime());
        $this->fileLogManager->saveAndFlush($fileLog);
    }

    /**
     * to execute on move file
     *
     * @param FileEvent $fileEvent
     */
    public function onMoveFile(FileEvent $fileEvent)
    {
        $fileLog = new FileLog();
        $logAction = $this->em->getRepository(FileLogAction::class)->find(Constant::FILE_LOG_ACTION_MOVE);
        $fileLog->setClient($this->tokenStorage->getToken()->getUser()->getClient())
            ->setFile($fileEvent->getFile())
            ->setFileLogAction($logAction)
            ->setUser($this->tokenStorage->getToken()->getUser())
            ->setReferer(null)
            ->setIp(getenv('REMOTE_ADDR'))
            ->setUserAgent(getenv('HTTP_USER_AGENT'));
        $this->fileLogManager->saveAndFlush($fileLog);
    }
    /**
     * to execute on copy file
     *
     * @param FileEvent $fileEvent
     */
    public function onCopyFile(FileEvent $fileEvent)
    {
        $fileLog = new FileLog();
        $logAction = $this->em->getRepository(FileLogAction::class)->find(Constant::FILE_LOG_ACTION_COPY);
        $fileLog->setClient($this->tokenStorage->getToken()->getUser()->getClient())
            ->setFile($fileEvent->getFile())
            ->setFileLogAction($logAction)
            ->setUser($this->tokenStorage->getToken()->getUser())
            ->setReferer(null)
            ->setIp(getenv('REMOTE_ADDR'))
            ->setUserAgent($_SERVER['HTTP_USER_AGENT'])
            ->setCreatedAt(new \DateTime());
        $this->fileLogManager->saveAndFlush($fileLog);
    }


    /**
     * to execute on copy file
     *
     * @param FileEvent $fileEvent
     */
    public function onCreateFile(FileEvent $fileEvent)
    {
        $fileLog = new FileLog();
        $logAction = $this->em->getRepository(FileLogAction::class)->find(Constant::FILE_LOG_ACTION_COPY);
        $fileLog->setClient($this->tokenStorage->getToken()->getUser()->getClient())
            ->setFile($fileEvent->getFile())
            ->setFileLogAction($logAction)
            ->setUser($this->tokenStorage->getToken()->getUser())
            ->setReferer(null)
            ->setIp(getenv('REMOTE_ADDR'))
            ->setUserAgent($_SERVER['HTTP_USER_AGENT'])
            ->setCreatedAt(new \DateTime());
        $this->fileLogManager->saveAndFlush($fileLog);
    }
}
