<?php

namespace AppBundle\Services;

use AppBundle\Entity\Constants\Constant;
use AppBundle\Entity\Folder;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use \Ovh\Sms\SmsApi;


class Sms
{

    private $container;
    private $scriptName;
    private $sendSmsDescription;
    private $ovhAppKey;
    private $ovhConsumerKey;
    private $entityManager;

    /**
     * Sms constructor.
     * @param ContainerInterface $container
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ContainerInterface $container, EntityManagerInterface $entityManager)
    {
        $this->container = $container;
        $this->entityManager = $entityManager;
        $this->scriptName = $this->container->getParameter('script_name');
        $this->sendSmsDescription = $this->container->getParameter("send_sms_description");
        $this->ovhAppKey = $this->container->getParameter("ovh_app_key");
        $this->ovhAppSecret = $this->container->getParameter("ovh_app_secret");
        $this->ovhConsumerKey = $this->container->getParameter("ovh_consumer_key");
    }


    /**
     * @param string $numeros
     * @param Folder $folder
     *
     * @return array
     */
    public function send($numeros, Folder $folder)
    {
        $modelEMail = $this->entityManager->getRepository("AdminBundle:EmailAutomatique")
            ->findBy(['declenchement' => Constant::SEND_SMS], ['id' => 'DESC'], 1);
        $data["receivers"] = [];
        $data["failed"] = [];
        if (isset($modelEMail[0])) {
            $template = $modelEMail[0]->getTemplate();
            $modele = ["__keyCrypt__"];
            $real = [$folder->getCryptPassword()];
            $template = str_replace($modele, $real, $template);
            $smsApi = new SmsApi($this->ovhAppKey, $this->ovhAppSecret, "ovh-eu", $this->ovhConsumerKey);
            $accounts = $smsApi->getAccounts();
            $smsApi->setAccount($accounts[0]);
            $senders = $smsApi->getSenders();
            $message = $smsApi->createMessage();
            $message->setSender($senders[0]);
            $numTab = array_unique(preg_split("/(;|,)/", $numeros));
            foreach ($numTab as $num) {
                if (strpos($num, ' ') === 0) {
                    $num = "+".trim($num);
                }
                if (preg_match("/^(\+|00)[1-9][0-9]{9,16}$/", $num) != 1) {
                    $data["failed"] = trim($num);
                } else {
                    $message->addReceiver($num);
                    $data["receivers"][] = $num;
                }
            }
            $message->setIsMarketing(false);
            $message->setDeliveryDate(new \DateTime('now'));
            $message->send(strip_tags($template));
            $plannedMessages = $smsApi->getPlannedMessages();
            foreach ($plannedMessages as $planned) {
                $planned->delete();
            }
        }

        return $data;
    }
}
