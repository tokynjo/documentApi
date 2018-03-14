<?php
/**
 * Created by PhpStorm.
 * User: Nasolo RANDIANINA
 * Date: 27/02/2018
 * Time: 16:31
 */
namespace AppBundle\EventListener;

use AppBundle\Manager\UserManager;
use AppBundle\Entity\Constants\Constant;
use AppBundle\Entity\FileLog;
use AppBundle\Entity\FileLogAction;
use AppBundle\Event\CommentEvent;
use AppBundle\Event\FileEvent;
use AppBundle\Manager\EmailAutomatiqueManager;
use AppBundle\Manager\FileLogManager;
use AppBundle\Services\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Comment event Listener
 * Listen comment event
 * @package AppBundle\EventListener
 */
class CommentListener
{
    private $mailer;
    private $em;
    private $mailAutoManager;
    private $userManager;

    /**
     * @param EntityManagerInterface $em
     * @param Mailer $mailer
     * @param EmailAutomatiqueManager $mailAutoManager
     * @param UserManager $userManager
     */
    public function __construct(
        EntityManagerInterface $em,
        Mailer $mailer,
        EmailAutomatiqueManager $mailAutoManager,
        UserManager $userManager
    )
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->mailAutoManager = $mailAutoManager;
        $this->userManager = $userManager;
        return $this;
    }


    /**
     * to execute on create comment
     * to sending email notification
     * @param CommentEvent $commentEvent
     * @return bool
     */
    public function onCreateComment(CommentEvent $commentEvent)
    {

        $modelEMail = $this->mailAutoManager->findBy(
            ['declenchement' => Constant::MAIL_COMMENT_NOTIFICATION],
            ['id' => 'DESC'],
            1
        );
        $emails = explode(',', $commentEvent->getToNotify());
        foreach($emails as $k => $email) {
            $to = $this->userManager->findBy(
                ['email' => $email, 'isDeleted'=>0],
                null,
                1
            );

            if (count($to) > 0) {
                $model = ["__userame__", "__commentator_name__", "__message__"];
                $data  = [
                    $to[0]->getInfosUser(),
                    $commentEvent->getComment()->getUser()->getFirstName(),
                    $commentEvent->getComment()->getMessage()
                ];
                $template = $this->mailAutoManager->replaceData($model, $data, $modelEMail[0]->getTemplate());
                $this->mailer->sendMailGrid($modelEMail[0]->getObjet(), $email, $template);
            }
        }
        return true;
    }
}
