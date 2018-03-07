<?php

namespace ApiBundle\Controller;

use AppBundle\Entity\Api\ApiResponse;
use AppBundle\Entity\Constants\Constant;
use AppBundle\Entity\Folder;
use AppBundle\Manager\EmailAutomatiqueManager;
use AppBundle\Manager\FileManager;
use AppBundle\Manager\FileUserManager;
use AppBundle\Manager\FolderManager;
use AppBundle\Manager\FolderUserManager;
use AppBundle\Manager\InvitationRequestManager;
use AppBundle\Manager\NewsManager;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use FOS\UserBundle\Event\GetResponseUserEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


class InvitationController extends Controller
{
    /**
     * Send invitation to adress email
     * @ApiDoc(
     *      resource=true,
     *      description = "send invitation to email",
     *      parameters = {
     *          {"name"="email", "dataType"="string", "required"=true, "description"="adress mail serapate ;"},
     *          {"name"="id_folder", "dataType"="integer", "required"=true, "description"="folder id"},
     *          {"name"="right", "dataType"="integer", "required"=false, "description"="right id"},
     *          {"name"="message", "dataType"="string", "required"=false, "description"="Message in email"}
     *      },
     *      headers={
     *         {"name"="Authorization", "required"=true, "description"="Authorization token"
     *         }
     *     }
     * )
     * @Route("/api/send-invitation",name="api_send_invitation")
     * @Method("POST")
     * @return View
     */
    public function sendInvitationAction(Request $request)
    {
        $data = [];
        if (!$request->get("email")) {
            return new JsonResponse(["code" => Response::HTTP_BAD_REQUEST, "message" => "Missing parameters email."]);
        }
        if (!$request->get("id_folder") && !$request->get("id_file")) {
            return new JsonResponse(["code" => Response::HTTP_BAD_REQUEST, "message" => "Missing parameters id_folder."]);
        }
        $message = $request->get("message");
        $right = null;
        $right_id = $request->get("right");
        $folder = null;
        $file = null;
        $id_folder = $request->get("id_folder");
        $id_file = $request->get("id_file");
        $data['email_share_fail'] = [];
        $data['email_share_success'] = [];
        if ($right_id) {
            $right = $this->getDoctrine()->getRepository("AppBundle:Right")->find($right_id);
        }
        $email = $request->get("email");
        $emails = array_unique(preg_split("/(;|,)/", $email));
        $tabAdress = array();
        foreach ($emails as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $tabAdress[] = $email;
            } else {
                $data['email_share_fail'][] = $email;
            }
        }
        if ($request->get("id_folder")) {
            $folder = $this->get(FolderManager::SERVICE_NAME)->find($id_folder);
            if (!$folder) {
                return new JsonResponse(["code" => Response::HTTP_NOT_ACCEPTABLE, "message" => "Folder not found"]);
            }
            if (!$this->getDroit($folder)) {
                return new JsonResponse(["code" => Response::HTTP_NOT_ACCEPTABLE, "message" => "Not a permission."]);
            }
        }
        if ($request->get("id_file")) {
            $file = $this->get(FileManager::SERVICE_NAME)->find($id_file);
            if (!$file) {
                return new JsonResponse(["code" => Response::HTTP_NOT_ACCEPTABLE, "message" => "File not found"]);
            }
            if (!$this->getDroit($file)) {
                return new JsonResponse(["code" => Response::HTTP_NOT_ACCEPTABLE, "message" => "Not a permission."]);
            }
        }
        foreach ($tabAdress as $email) {
            $invitationManager = $this->get(InvitationRequestManager::SERVICE_NAME);
            $this->sendMailCreateUser($email);
            if ($folder) {
                $invtExist = $invitationManager->findBy(array("email" => $email, "folder" => $folder));
            } else {
                $invtExist = $invitationManager->findBy(array("email" => $email, "fichier" => $file));
            }
            if ($invtExist) {
                $data['email_share_fail'][] = $email;
            } else {
                $new_invitation = $invitationManager->createInvitation($message, $email, $folder, $file, $this->getUser(), $right, $request->get("synchro"));
                $result = $this->sendUrlByMail($email, $message, $folder, $file, $new_invitation);
                if ($result) {
                    $data['email_share_success'][] = $email;
                } else {
                    $data['email_share_fail'][] = $email;
                }
            }
        }
        $resp = new ApiResponse();
        $respStatus = Response::HTTP_OK;
        $resp->setCode(Response::HTTP_OK);
        $resp->setData($data);
        return new View($resp, $respStatus);
    }


    /**
     * send email and create user
     * @param $adress
     * @return mixed
     */
    public function sendMailCreateUser($adress)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserByEmail($adress);
        if (!$user) {
            $user = $userManager->createUser();
            $user->setEnabled(true);
            $user->setEmail($adress);
            $user->setCreatedIp(getenv('SERVER_ADDR'));
            $user->setConfirmationToken(md5($adress . time()));
            $password = substr(md5($user->getUsername()), 0, 10);
            $user->setPlainPassword($password);
            $user->setUserName($user->getEmail());
            $userManager->updateUser($user);
            $modelEMail = $this->get(EmailAutomatiqueManager::SERVICE_NAME)->findBy(
                ['declenchement' => Constant::CREATE_USER], ['id' => 'DESC'], 1);
            $template = $modelEMail[0]->getTemplate();
            $modele = ["__utilisateur__", "__password__"];
            $real = [$adress, $password];
            $template = str_replace($modele, $real, $template);
            $mailer = $this->get("app.mailer");
            return $mailer->sendMailGrid($modelEMail[0]->getObjet(), $adress, $template);
        }
    }

    /**
     * @param $adress
     * @param $folder
     * @param $new_invitation
     */
    public function sendUrlByMail($adress, $message, $folder, $file, $new_invitation)
    {
        $userCurrent = $this->getUser();
        $modelEMail = $this->get(EmailAutomatiqueManager::SERVICE_NAME)->findBy(
            ['declenchement' => Constant::SEND_INVITATION],
            ['id' => 'DESC'], 1);
        $template = $modelEMail[0]->getTemplate();
        $nameFileFolder = ($folder) ? $folder->getName() : $file->getName();
        $url = "<a href='" . $this->getParameter("host_preprod") .
            "?token=" . $new_invitation->getToken() . "'>" . $nameFileFolder . "</a>";
        $modele = ["__url__", "__username__", "__name_folder__", "__message__"];
        $real = [$url,
            $userCurrent->getFirstName(),
            $nameFileFolder,
            $message
        ];
        $template = str_replace($modele, $real, $template);
        $mailer = $this->get("app.mailer");
        return $mailer->sendMailGrid($modelEMail[0]->getObjet(), $adress, $template);
    }

    public function getDroit($folder)
    {
        if ($folder->getUser() == $this->getUser()) {
            return true;
        } else {
            if ($folder instanceof Folder) {
                $droit = $this->getDoctrine()->getRepository("AppBundle:FolderUser")->getDroitOfUser($this->getUser(), $folder);
            } else {
                $droit = $this->getDoctrine()->getRepository("AppBundle:FileUser")->getDroitOfUser($this->getUser(), $folder);
            }
            if ($droit) {
                return true;
            }
        }
        return false;
    }
}
