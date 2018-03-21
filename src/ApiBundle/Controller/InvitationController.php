<?php

namespace ApiBundle\Controller;

use AppBundle\Entity\Api\ApiResponse;
use AppBundle\Entity\Constants\Constant;
use AppBundle\Entity\Folder;
use AppBundle\Entity\InvitationRequest;
use AppBundle\Entity\Right;
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

    private $folder;
    private $file;

    /**
     * Send invitation to adress email
     *
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
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"
     *         }
     *     }
     * )
     * @Route("/api/send-invitation",name="api_send_invitation")
     * @Method("POST")
     * @return                                                   View
     */
    public function sendInvitationAction(Request $request)
    {
        $resp = new ApiResponse();
        if ($this->verifyParam($request) !== true) {
            return $this->verifyParam($request);
        }
        $right = null;
        $data['email_share_fail'] = [];
        $data['email_share_success'] = [];
        if ($request->get("right")) {
            $right = $this->getDoctrine()->getRepository("AppBundle:Right")->find($request->get("right"));
        }
        $data = $this->createInvitationByEmail($request, $right);
        $resp->setCode(Response::HTTP_OK);
        $resp->setData($data);

        return new View($resp, Response::HTTP_OK);
    }


    /**
     * Send email and create user
     * @param  string $adress
     *
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
            $user->setConfirmationToken(md5($adress.time()));
            $password = substr(md5($user->getUsername()), 0, 10);
            $user->setPlainPassword($password);
            $user->setUserName($user->getEmail());
            $userManager->updateUser($user);
            $modelEMail = $this->get(EmailAutomatiqueManager::SERVICE_NAME)->findBy(
                ['declenchement' => Constant::CREATE_USER, 'deletedAt' => null],
                ['id' => 'DESC'],
                1
            );
            $dataFrom['send_by'] = $modelEMail[0]->getEmitter();
            $template = $modelEMail[0]->getTemplate();
            $modele = ["__utilisateur__", "__password__"];
            $real = [$adress, $password];
            $template = str_replace($modele, $real, $template);
            $mailer = $this->get("app.mailer");

            return $mailer->sendMailGrid($modelEMail[0]->getObjet(), $adress, $template, $dataFrom);
        }
    }

    /**
     * @param string            $adress
     * @param string            $message
     * @param Folder            $folder
     * @param File              $file
     * @param InvitationRequest $inv
     *
     * @return null
     */
    public function sendUrlByMail($adress, $message, Folder $folder = null, File $file = null, InvitationRequest $inv)
    {
        $userCurrent = $this->getUser();
        $modelEMail = $this->get(EmailAutomatiqueManager::SERVICE_NAME)
            ->findBy(['declenchement' => Constant::SEND_INVITATION], ['id' => 'DESC'], 1);
        $nameFileFolder = ($folder) ? $folder->getName() : $file->getName();
        $url = "<a href='".$this->getParameter("host_preprod")."?token=".$inv->getToken()."'>".$nameFileFolder."</a>";
        $modele = ["__url__", "__username__", "__name_folder__", "__message__"];
        $real = [$url, $userCurrent->getInfosUser(), $nameFileFolder, $message];
        $template = str_replace($modele, $real, $modelEMail[0]->getTemplate());
        $mailer = $this->get("app.mailer");
        $dataFrom['send_by'] = $modelEMail[0]->getEmitter();

        return $mailer->sendMailGrid($modelEMail[0]->getObjet(), $adress, $template, $dataFrom);
    }

    /**
     * @param Folder $folder
     *
     * @return bool
     */
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

    /**
     * @param  Request $request
     * @return bool|JsonResponse
     */
    public function verifyParam(Request $request)
    {
        if (!$request->get("email")) {
            return new JsonResponse(["code" => Response::HTTP_BAD_REQUEST, "message" => "Missing parameters email."]);
        }
        if (!$request->get("id_folder") && !$request->get("id_file")) {
            return new JsonResponse(["code" => Response::HTTP_BAD_REQUEST, "message" => "Missing parameters id_folder."]);
        }
        if ($request->get("id_folder")) {
            $this->folder = $this->get(FolderManager::SERVICE_NAME)->find($request->get("id_folder"));
            if (!$this->folder) {
                return new JsonResponse(["code" => Response::HTTP_NOT_ACCEPTABLE, "message" => "Folder not found"]);
            }
            if (!$this->getDroit($this->folder)) {
                return new JsonResponse(["code" => Response::HTTP_NOT_ACCEPTABLE, "message" => "Not a permission."]);
            }
        }
        if ($request->get("id_file")) {
            $this->file = $this->get(FileManager::SERVICE_NAME)->find($request->get("id_file"));
            if (!$this->file) {
                return new JsonResponse(["code" => Response::HTTP_NOT_ACCEPTABLE, "message" => "File not found"]);
            }
            if (!$this->getDroit($this->file)) {
                return new JsonResponse(["code" => Response::HTTP_NOT_ACCEPTABLE, "message" => "Not a permission."]);
            }
        }
        return true;
    }

    /**
     * @param Request    $request
     * @param Right|null $right
     * @return mixed
     */
    public function createInvitationByEmail(Request $request, Right $right = null)
    {
        $email = $request->get("email");
        $message = $request->get("message");
        $emails = array_unique(preg_split("/(;|,)/", $email));
        foreach ($emails as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $invitationManager = $this->get(InvitationRequestManager::SERVICE_NAME);
                $this->sendMailCreateUser($email);
                if ($this->folder) {
                    $invExist = $invitationManager->findBy(array("email" => $email, "folder" => $this->folder));
                } else {
                    $invExist = $invitationManager->findBy(array("email" => $email, "fichier" => $this->file));
                }
                if ($invExist) {
                    $data['email_share_fail'][] = $email;
                } else {
                    $inv = $invitationManager->createInvitation($message, $email, $this->folder, $this->file, $this->getUser(), $right, $request->get("synchro"));
                    $result = $this->sendUrlByMail($email, $message, $this->folder, $this->file, $inv);
                    if ($result) {
                        $data['email_share_success'][] = $email;
                    } else {
                        $data['email_share_fail'][] = $email;
                    }
                }
            } else {
                $data['email_share_fail'][] = $email;
            }
        }

        return $data;
    }
}
