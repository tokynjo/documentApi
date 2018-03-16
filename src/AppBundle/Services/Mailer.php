<?php

namespace AppBundle\Services;

use AppBundle\Entity\Constants\Constant;
use AppBundle\Entity\Folder;
use AppBundle\Manager\EmailAutomatiqueManager;
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
        $result = $mail->send($message, $faillures);
        return [
            "success" => $result,
            "fails" => $faillures
        ];
    }


    /**
     * @param $subject
     * @param $mailTo
     * @param $template
     * @param null     $dataFrom
     * @return null
     */
    public function sendMailGrid($subject, $mailTo, $template, $dataFrom = null)
    {
        $container = $this->container;
        if (!empty($dataFrom['send_by'])) {
            $user = $dataFrom['send_by'];
        } else {
            $user = $container->get('security.token_storage')->getToken()->getUser();
        }
        $data = [];
        if (isset($dataFrom['pj'])) {
            foreach ($dataFrom['pj'] as $fichier) {
                $attachments = new \stdClass();
                if ($fichier instanceof \Swift_Attachment) {
                    $attachments->type = $fichier->getContentType();
                    $attachments->filename = $fichier->getFilename();
                    $attachments->content = base64_encode($fichier->getBody());
                } else {
                    $fileObject = \Swift_Attachment::fromPath($fichier);
                    $attachments->type = $fileObject->getContentType();
                    $attachments->filename = $fileObject->getFilename();
                    $attachments->content = base64_encode(file_get_contents($fichier));
                }
                $data['attachments'][] = $attachments;
            }
        }
        $data['personalizations'] = [];
        $pres = new \stdClass();
        $to = new \stdClass();
        $to->email = $mailTo;
        $pres->to[] = $to;
        if (isset($dataFrom['cc'])) {
            foreach ($dataFrom['cc'] as $ccMail) {
                if ($ccMail) {
                    $cc = new \stdClass();
                    $cc->email = $ccMail;
                    $pres->cc[] = $cc;
                }
            }
        }
        if (isset($dataFrom['cci'])) {
            foreach ($dataFrom['cci'] as $cciMail) {
                if ($cciMail) {
                    $cci = new \stdClass();
                    $cci->email = $cciMail;
                    $pres->bcc[] = $cci;
                }
            }
        }
        $pres->subject = $subject;
        $data['personalizations'][] = $pres;
        $from = new \stdClass();
        $from->email = $user->getEmail();
        $from->name = $user->getLastName() . " " . $user->getFirstname();
        $data['from'] = $from;
        $data['content'] = [];
        $content = new \stdClass();
        $content->type = "text/html";
        $content->value = $template;
        $data['content'][] = $content;
        $body = json_encode($data);
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            "Authorization" => "Bearer " . $container->getParameter('api_key_send_grid')
        ];
        $response = \Unirest\Request::post('https://api.sendgrid.com/v3/mail/send', $headers, $body);
        $response->code;
        $response->headers;
        $response->body;
        $response->raw_body;
        if (isset($response->headers['X-Message-Id'])) {
            return $response->headers['X-Message-Id'];
        }
        return null;
    }


    /**
     * Send email for url of folder with code cryptage
     *
     * @param  $adress
     * @param  $message
     * @param  Folder  $folder
     * @return null
     */
    public function sendUrlByMail($adress, $message, Folder $folder)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $modelEMail = $this->container->get(EmailAutomatiqueManager::SERVICE_NAME)->findBy(
            ['declenchement' => Constant::SEND_CODE_CRYPT],
            ['id' => 'DESC'], 1
        );
        if (isset($modelEMail[0])) {
            $template = $modelEMail[0]->getTemplate();
            $nameFileFolder = $folder->getName();
            $url = '<a href=" ' . $this->container->getParameter("host_preprod") . ' ">' . $nameFileFolder . '</a>';
            $modele = ["__url__", "__utilisateur__", "__nom_dossier__", "__code__", "__message__"];
            $real = [$url, $user->getInfosUser(), $nameFileFolder, $folder->getCryptPassword(), $message];
            $template = str_replace($modele, $real, $template);
            return $this->sendMailGrid($modelEMail[0]->getObjet(), $adress, $template);
        }
    }
}
