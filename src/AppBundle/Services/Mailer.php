<?php

namespace AppBundle\Services;

use Psr\Container\ContainerInterface;

class Mailer
{
    private $mailer;
    private $container;

    public function __construct(ContainerInterface $container, \Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
        $this->container = $container;
    }

    /**
     * @param $subject
     * @param $mailTo
     * @param $template
     * @return int
     */
    public function sendMail($subject, $mailTo, $template)
    {
        $container = $this->container;
        $user = $container->get('security.token_storage')->getToken()->getUser();
        $mail = $this->mailer;
        $message = (new \Swift_Message($subject))
            ->setSubject($subject)
            ->setFrom("nrandianina@bocasay.com")
            ->setTo($mailTo)
            ->setCharset('UTF-8')
            ->setContentType('text/plain')
            ->setBody(
                $template,
                'text/html'
            );
        return $mail->send($message);
    }
}
