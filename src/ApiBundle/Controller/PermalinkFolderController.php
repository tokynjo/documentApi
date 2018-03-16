<?php

namespace ApiBundle\Controller;


use AppBundle\Entity\Api\ApiResponse;
use AppBundle\Entity\Constants\Constant;
use AppBundle\Entity\FileLogAction;
use AppBundle\Entity\Folder;
use AppBundle\Entity\FolderLogAction;
use AppBundle\Manager\EmailAutomatiqueManager;
use AppBundle\Manager\FolderLogManager;
use AppBundle\Manager\FolderManager;
use AppBundle\Manager\FolderUserManager;
use AppBundle\Manager\UrlMappingManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class PermalinkFolderController extends Controller
{
    /**
     * Get permalink
     *
     * @ApiDoc(
     *      resource=true,
     *      description="Generate permalink-folder",
     *      parameters = {
     *          {"name"="folder_id", "dataType"="integer", "required"=true, "description"="documentation.folder.id_folder"},
     *     },
     *     headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"
     *         }
     *     },
     *     statusCodes = {
     *        200 = "Success",
     *        204 = "Folder not found",
     *        400 = "Missing parameter",
     *        403 = "Do not have permission to this folder",
     *        500 = "Internal server error",
     *    }
     * )
     * @Method("POST")
     * @Route(path="/api/folder/get-permalink", name="api_folder_get_permalink")
     */
    public function generateByFolderAction(Request $request)
    {
        $resp = new ApiResponse();
        $verifyAccesFolder = $this->verifyAccesFolder($request);
        if ($verifyAccesFolder !== true) {
            return $verifyAccesFolder;
        }
        $folder = $this->get(FolderManager::SERVICE_NAME)->getPelmalink($request->get("folder_id"));
        $folder[0]["url"] = ($folder[0]["code"]) ? $this->getParameter("host_permalink") . "/" . $folder[0]["code"] : "";
        $resp->setData($folder[0]);
        return new View($resp, Response::HTTP_OK);
    }

    /**
     * @ApiDoc(
     *      resource=true,
     *      description="Disabled permalink of folder",
     *      parameters = {
     *          {"name"="folder_id", "dataType"="integer", "required"=true, "description"="documentation.folder.id_folder"},
     *     },
     *     headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"
     *         }
     *     },
     *     statusCodes = {
     *        200 = "Success",
     *        204 = "Folder not found",
     *        400 = "Missing parameter",
     *        403 = "Do not have permission to this folder",
     *        500 = "Internal server error",
     *    }
     * )
     * @Method("POST")
     * @Route(path="/api/folder/disabled-permalink", name="api_share_permalink_folder")
     * @param Request $request
     * @return View
     */
    public function disabledFolderAction(Request $request)
    {
        $resp = new ApiResponse();
        $verifyAccesFolder = $this->verifyAccesFolder($request);
        if ($verifyAccesFolder !== true) {
            return $verifyAccesFolder;
        }
        $folderManager = $this->get(FolderManager::SERVICE_NAME);
        $folder = $folderManager->find($request->get("folder_id"));
        $folder->setPermalink(0)
            ->setShare(Constant::NOT_SHARED);
        $folderManager->saveAndFlush($folder);
        return new View($resp, Response::HTTP_OK);
    }

    /**
     * @ApiDoc(
     *      resource=true,
     *      description="Protect with a password a permalink of folder",
     *      parameters = {
     *          {"name"="folder_id", "dataType"="integer", "required"=true, "description"="documentation.folder.id_folder"},
     *          {"name"="password", "dataType"="string", "required"=false, "description"="documentation.folder.password"}
     *     },
     *     headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"
     *         }
     *     },
     *     statusCodes = {
     *        200 = "Success",
     *        204 = "Folder not found",
     *        400 = "Missing parameter",
     *        403 = "Do not have permission to this folder",
     *        500 = "Internal server error",
     *    }
     * )
     * @Method("POST")
     * @Route(path="/api/folder/set-password-permalink", name="api_set_password_permalink_folder")
     * @param Request $request
     * @return View
     */
    public function setPassWordFolderAction(Request $request)
    {
        $resp = new ApiResponse();
        $verifyAccesFolder = $this->verifyAccesFolder($request);
        if ($verifyAccesFolder !== true) {
            return $verifyAccesFolder;
        }
        $folderManager = $this->get(FolderManager::SERVICE_NAME);
        $folder = $folderManager->find($request->get("folder_id"));
        $folder->setSharePassword($request->get("password"));
        if ($request->get("password")) {
            $this->createAction(Constant::FOLDER_LOG_ACTION_SHARE_PASSWORD, $folder);
        } else {
            $this->createAction(Constant::FOLDER_LOG_ACTION_SHARE_NOT_PASSWORD, $folder);
        }
        $folderManager->saveAndFlush($folder);
        $folder = $folderManager->getPelmalink($request->get("folder_id"));
        $folder[0]["url"] = ($folder[0]["code"]) ? $this->getParameter("host_permalink") . "/" . $folder[0]["code"] : "";
        $resp->setData($folder[0]);
        return new View($resp, Response::HTTP_OK);
    }

    /**
     * @ApiDoc(
     *      resource=true,
     *      description="Regenerate permalink of folder",
     *      parameters = {
     *          {"name"="folder_id", "dataType"="integer", "required"=true, "description"="documentation.folder.id_folder"},
     *     },
     *     headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"
     *         }
     *     },
     *     statusCodes = {
     *        200 = "Success",
     *        204 = "Folder not found",
     *        400 = "Missing parameter",
     *        403 = "Do not have permission to this folder",
     *        500 = "Internal server error",
     *    }
     * )
     * Regenerate new permalink
     * @Method("POST")
     * @Route(path="/api/folder/generate-permalink", name="api_generate_permalink_folder")
     * @param Request $request
     * @return View
     */
    public function generateFolderAction(Request $request)
    {
        $resp = new ApiResponse();
        $verifyAccesFolder = $this->verifyAccesFolder($request);
        if ($verifyAccesFolder !== true) {
            return $verifyAccesFolder;
        }
        $folderManager = $this->get(FolderManager::SERVICE_NAME);
        $folder = $folderManager->find($request->get("folder_id"));
        $permalien = $this->get("app.peramlink")->generate();
        $code = $this->get("app.peramlink")->generate();
        $url = $this->getParameter("host_preprod") . "/share/" . $permalien . "/folder/" . $folder->getId();
        $folder->setPermalink($permalien)
            ->setShare(Constant::SHARED);
        $folderManager->saveAndFlush($folder);
        $this->get(UrlMappingManager::SERVICE_NAME)->create($code, $url);
        $folder = $folderManager->getPelmalink($request->get("folder_id"));
        $folder[0]["url"] = ($folder[0]["code"]) ? $this->getParameter("host_permalink") . "/" . $folder[0]["code"] : "";
        $resp->setData($folder[0]);
        return new View($resp, Response::HTTP_OK);
    }

    /**
     * @ApiDoc(
     *      resource=true,
     *      description="Send by email a permalink of folder",
     *      parameters = {
     *          {"name"="folder_id", "dataType"="integer", "required"=true, "description"="documentation.folder.id_folder"},
     *          {"name"="email", "dataType"="string", "required"=true, "description"="documentation.folder.email"}
     *     },
     *     headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"
     *         }
     *     },
     *     statusCodes = {
     *        200 = "Success",
     *        204 = "Folder not found",
     *        400 = "Missing parameter",
     *        403 = "Do not have permission to this folder",
     *        500 = "Internal server error",
     *    }
     * )
     * @Method("POST")
     * @Route(path="/api/folder/send-permalink", name="api_folder_send_permalink")
     * @param Request $request
     * @return View
     */
    public function sendMailFolderAction(Request $request)
    {
        $resp = new ApiResponse();
        $verifyAccesFolder = $this->verifyAccesFolder($request);
        if ($verifyAccesFolder !== true) {
            return $verifyAccesFolder;
        }
        if (!$request->get("email")) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('Missing mandatory parameters.');
            return new JsonResponse($resp);
        }
        $folder = $this->get(FolderManager::SERVICE_NAME)->find($request->get("folder_id"));
        foreach (array_unique(preg_split("/(;|,)/", $request->get("email"))) as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->sendUrlByMail($email, $request->get("message"), $folder);
                $data['email_share_success'][] = $email;
            } else {
                $data['email_share_fail'][] = $email;
            }
        }
        $resp->setData($data);
        return new View($resp, Response::HTTP_OK);
    }

    /**
     * @param $adress
     * @param $message
     * @param $folder
     * @param $file
     * @param $url
     * @return mixed
     */
    public function sendUrlByMail($adress, $message, $folder)
    {
        $modelEMail = $this->get(EmailAutomatiqueManager::SERVICE_NAME)->findBy(
            ['declenchement' => Constant::SEND_INVITATION],
            ['id' => 'DESC'], 1
        );
        $template = $modelEMail[0]->getTemplate();
        $nameFileFolder = $folder->getName();
        $folder = $this->get(FolderManager::SERVICE_NAME)->getPelmalink($folder->getId());
        $file[0]["url"] = ($folder[0]["code"]) ? $this->getParameter("host_permalink") . "/" . $folder[0]["code"] : "";
        $url = '<a href=" ' . $file[0]["url"] . ' ">' . $nameFileFolder . '</a>';
        $modele = ["__url__", "__username__", "__name_folder__", "__message__"];
        $real = [$url, $this->getUser()->getInfosUser(), $nameFileFolder, $message];
        $template = str_replace($modele, $real, $template);
        $mailer = $this->get("app.mailer");
        return $mailer->sendMailGrid($modelEMail[0]->getObjet(), $adress, $template);
    }

    /**
     * @param $request
     * @return bool|JsonResponse
     */
    public function verifyAccesFolder($request)
    {
        $resp = new ApiResponse();
        if (!$request->get("folder_id")) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('Missing mandatory parameters.');
            return new JsonResponse($resp);
        }
        $folderManager = $this->get(FolderManager::SERVICE_NAME);
        $folder = $folderManager->find($request->get("folder_id"));
        if (!$folder) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('Folder not found.');
            return new JsonResponse($resp);
        }
        $tab_right = [Constant::RIGHT_MANAGER, Constant::RIGHT_CONTRIBUTOR];
        if (!$this->get(FolderUserManager::SERVICE_NAME)->getRightUser($folder, $this->getUser(), $tab_right)) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('Do not have permission to this folder');
            return new JsonResponse($resp);
        }
        return true;
    }

    /**
     * @param $event
     * @param $folder
     */
    public function createAction($event, $folder)
    {
        $eventAction = $this->getDoctrine()->getRepository(FolderLogAction::class)->find($event);
        $this->get(FolderLogManager::SERVICE_NAME)->createLog($eventAction, $this->getUser(), $folder);
    }
}
