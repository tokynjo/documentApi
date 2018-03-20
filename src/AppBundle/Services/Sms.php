<?php

namespace AppBundle\Services;

use AppBundle\Entity\Constants\Constant;
use AppBundle\Entity\Folder;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use \Ovh\Api;


class Sms
{

    private $container;
    private $scriptName;
    private $sendSmsDescription;
    private $ovhAppKey;
    private $ovhSppSecret;
    private $ovhConsumerKey;
    private $entityManager;

    /**
     * Sms constructor.
     * @param ContainerInterface     $container
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
        if (isset($modelEMail[0])) {
            $template = $modelEMail[0]->getTemplate();
            $modele = ["__keyCrypt__"];
            $real = [$folder->getCryptPassword()];
            $template = str_replace($modele, $real, $template);
            $conn = new Api($this->ovhAppKey, $this->ovhAppSecret, "ovh-eu", $this->ovhConsumerKey);
            $smsServices = $conn->get('/sms/');
            $numTab = array_unique(preg_split("/(;|,)/", $numeros));
            $data = [
                "charset" => "UTF-8",
                "class" => "phoneDisplay",
                "coding" => "7bit",
                "message" => strip_tags($template),
                "noStopClause" => false,
                "priority" => "high",
                "receivers" => $numTab,
                "senderForResponse" => true,
                "validityPeriod" => 2880
            ];
            $content = (object) $data;
            $resultPostJob = $conn->post('/sms/'.$smsServices[0].'/jobs/', $content);
        }

        return $resultPostJob;
    }
}
