<?php

namespace ApiBundle\Controller;

use AppBundle\Entity\Api\ApiResponse;
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
     *          {"name"="right", "dataType"="integer", "required"=false, "description"="right id"}
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
            return new JsonResponse(
                [
                    "code" => Response::HTTP_BAD_REQUEST,
                    "message" => "Missing parameters email."
                ]);
        }
        if (!$request->get("id_folder") && !$request->get("id_file")) {
            return new JsonResponse(
                [
                    "code" => Response::HTTP_BAD_REQUEST,
                    "message" => "Missing parameters id_file ou id_folder."
                ]);
        }
        $right_id = $request->get("right");
        $right = null;
        if ($right_id) {
            $right = $this->getDoctrine()->getRepository("AppBundle:Right")->find($right_id);
        }
        $email = $request->get("email");
        $tabAdress = explode(";", $email);
        $folder = null;
        $file = null;
        $id_folder = $request->get("id_folder");
        $id_file = $request->get("id_file");
        $right = null;
        if ($request->get("id_folder")) {
            $folder = $this->get(FolderManager::SERVICE_NAME)->find($id_folder);
            if (!$folder) {
                return new JsonResponse(
                    [
                        "code" => Response::HTTP_NOT_ACCEPTABLE,
                        "message" => "Folder not found"
                    ]);
            }
        }
        if ($request->get("id_file")) {
            $file = $this->get(FileManager::SERVICE_NAME)->find($id_file);
            if (!$file) {
                return new JsonResponse(
                    [
                        "code" => Response::HTTP_NOT_ACCEPTABLE,
                        "message" => "File not found"
                    ]);
            }
        }
        $data['email_share_fail'] = $this->getDoctrine()
            ->getRepository("AppBundle:InvitationRequest")->getEmailByFolderFile($tabAdress, $id_folder, $id_file);
        $data['email_share_succes'] = [];
        foreach ($tabAdress as $email) {
            if (!in_array($email, $data['email_share_fail'])) {
                $invitationManager = $this->get(InvitationRequestManager::SERVICE_NAME);
                $new_invitation = $invitationManager->createInvitation($email, $folder, $file, $this->getUser(), $right);
                $data['email_share_succes'][] = $email;
                $newUser = $this->sendMailCreateUser($email);
                $this->sendUrlByMail($email, $folder, $file, $new_invitation);
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
            $mailer = $this->get("app.mailer");
            $template = $this->renderView('Email/invitation.html.twig', array(
                    'user' => $user,
                    'mdp' => $password,
                    "host_preprod" => $this->getParameter("host_preprod")
                    )
            );
            $mailer->sendMail("Mail", $adress, $template);
        }
        return $user;
    }

    /**
     * @param $adress
     * @param $folder
     * @param $new_invitation
     */
    public function sendUrlByMail($adress, $folder, $file, $new_invitation)
    {
        $userCurrent = $this->getUser();
        $mailer = $this->get("app.mailer");
        $template = $this->renderView('Email/send-url.html.twig',
            [
                "user" => $userCurrent,
                "folder" => $folder,
                "file" => $file,
                "host_preprod" => $this->getParameter("host_preprod"),
                "url" => $this->getParameter("host_preprod") . "?token=" . $new_invitation->getToken()
            ]
        );
        $mailer->sendMail("Mail", $adress, $template);
    }
}
