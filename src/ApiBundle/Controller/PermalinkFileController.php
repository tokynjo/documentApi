<?php

namespace ApiBundle\Controller;


use AppBundle\Entity\Api\ApiResponse;
use AppBundle\Entity\Constants\Constant;
use AppBundle\Entity\FileLogAction;
use AppBundle\Entity\Folder;
use AppBundle\Entity\FolderLogAction;
use AppBundle\Manager\EmailAutomatiqueManager;
use AppBundle\Manager\FileLogManager;
use AppBundle\Manager\FileManager;
use AppBundle\Manager\FileUserManager;
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

class PermalinkFileController extends Controller
{
    /**
     * Get permalink of file
     *
     * @ApiDoc(
     *      resource=true,
     *      description="Generate permalink-file",
     *      parameters = {
     *          {"name"="file_id", "dataType"="integer", "required"=true, "description"="documentation.file.file_id"},
     *     },
     *     headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"
     *         }
     *     },
     *     statusCodes = {
     *        200 = "Success",
     *        204 = "File not found",
     *        400 = "Missing parameter",
     *        403 = "Do not have permission to this file",
     *        500 = "Internal server error",
     *    }
     * )
     * @Method("POST")
     * @Route(path="/api/file/get-permalink", name="api_file_get_permalink")
     */
    public function generateByFileAction(Request $request)
    {
        $resp = new ApiResponse();
        $verifyAccesFile = $this->get("app.peramlink")->verifyAccesFile($request);
        if ($verifyAccesFile !== true) {
            return $verifyAccesFile;
        }
        $file = $this->get(FileManager::SERVICE_NAME)->getPelmalink($request->get("file_id"));
        $file[0]["url"] = ($file[0]["code"]) ? $this->getParameter("host_permalink") . "/" . $file[0]["code"] : "";
        $resp->setData($file[0]);
        return new View($resp, Response::HTTP_OK);
    }


    /**
     * @ApiDoc(
     *      resource=true,
     *      description="Disabled permalink of file",
     *      parameters = {
     *          {"name"="folder_id", "dataType"="integer", "required"=true, "description"="documentation.folder.id_folder"},
     *     },
     *     headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"
     *         }
     *     },
     *     statusCodes = {
     *        200 = "Success",
     *        204 = "File not found",
     *        400 = "Missing parameter",
     *        403 = "Do not have permission to this file",
     *        500 = "Internal server error",
     *    }
     * )
     * @Method("POST")
     * @Route(path="/api/file/disabled-permalink", name="api_share_permalink_file")
     * @param Request $request
     * @return View
     */
    public function disabledFileAction(Request $request)
    {
        $resp = new ApiResponse();
        $verifyAccesFile = $this->get("app.peramlink")->verifyAccesFile($request);
        if ($verifyAccesFile !== true) {
            return $verifyAccesFile;
        }
        $fileManager = $this->get(FileManager::SERVICE_NAME);
        $file = $fileManager->find($request->get("file_id"));
        $file->setPermalink(0);
        $file->setShare(Constant::NOT_SHARED);
        $fileManager->saveAndFlush($file);
        if ($request->get("share") == Constant::SHARED) {
            $this->createAction(Constant::FILE_LOG_ACTION_SHARE, $file);
        } else {
            $this->createAction(Constant::FILE_LOG_ACTION_NOT_SHARE, $file);
        }
        return new View($resp, Response::HTTP_OK);
    }

    /**
     * Protect with password
     *
     * @ApiDoc(
     *      resource=true,
     *      description="Protect with a password a permalink of file",
     *      parameters = {
     *          {"name"="file_id", "dataType"="integer", "required"=true, "description"="documentation.file.file_id"},
     *          {"name"="password", "dataType"="string", "required"=false, "description"="documentation.file.password"}
     *     },
     *     headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"
     *         }
     *     },
     *     statusCodes = {
     *        200 = "Success",
     *        204 = "File not found",
     *        400 = "Missing parameter",
     *        403 = "Do not have permission to this file",
     *        500 = "Internal server error",
     *    }
     * )
     * @Method("POST")
     * @Route(path="/api/file/set-password-permalink", name="api_set_password_permalink_file")
     * @param                                          Request $request
     * @return                                         View
     */
    public function setPassWordFileAction(Request $request)
    {
        $resp = new ApiResponse();
        $verifyAccesFile = $this->get("app.peramlink")->verifyAccesFile($request);
        if ($verifyAccesFile !== true) {
            return $verifyAccesFile;
        }
        $fileManager = $this->get(FileManager::SERVICE_NAME);
        $file = $fileManager->find($request->get("file_id"));
        $file->setSharePassword($request->get("password"));
        $fileManager->saveAndFlush($file);
        if ($request->get("password")) {
            $this->createAction(Constant::FILE_LOG_ACTION_SHARE_PASSWORD, $file);
        } else {
            $this->createAction(Constant::FILE_LOG_ACTION_SHARE_NOT_PASSWORD, $file);
        }
        $file = $this->get(FileManager::SERVICE_NAME)->getPelmalink($request->get("file_id"));
        $file[0]["url"] = ($file[0]["code"]) ? $this->getParameter("host_permalink") . "/" . $file[0]["code"] : "";
        $resp->setData($file[0]);
        return new View($resp, Response::HTTP_OK);
    }

    /**
     * @ApiDoc(
     *      resource=true,
     *      description="Regenerate permalink of file",
     *      parameters = {
     *          {"name"="file_id", "dataType"="integer", "required"=true, "description"="documentation.file.file_id"},
     *     },
     *     headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"
     *         }
     *     },
     *     statusCodes = {
     *        200 = "Success",
     *        204 = "File not found",
     *        400 = "Missing parameter",
     *        403 = "Do not have permission to this file",
     *        500 = "Internal server error",
     *    }
     * )
     * Regenerate new permalink
     * @Method("POST")
     * @Route(path="/api/file/generate-permalink", name="api_generate_permalink_file")
     * @param Request $request
     * @return View
     */
    public function generateFileAction(Request $request)
    {
        $resp = new ApiResponse();
        $verifyAccesFile = $this->get("app.peramlink")->verifyAccesFile($request);
        if ($verifyAccesFile !== true) {
            return $verifyAccesFile;
        }
        $fileManager = $this->get(FileManager::SERVICE_NAME);
        $file = $fileManager->find($request->get("file_id"));
        $permalien = $this->get("app.peramlink")->generate();
        $code = $this->get("app.peramlink")->generate();
        $url = $this->getParameter("host_preprod") . "/share/" . $permalien . "/file/" . $file->getId();
        $file->setPermalink($permalien);
        $file->setShare(Constant::SHARED);
        $this->get(UrlMappingManager::SERVICE_NAME)->create($code, $url);
        $fileManager->saveAndFlush($file);
        $file = $this->get(FileManager::SERVICE_NAME)->getPelmalink($request->get("file_id"));
        $file[0]["url"] = ($file[0]["code"]) ? $this->getParameter("host_permalink") . "/" . $file[0]["code"] : "";
        $resp->setData($file[0]);
        return new View($resp, Response::HTTP_OK);
    }


    /**
     * @ApiDoc(
     *      resource=true,
     *      description="Send by email a permalink of file",
     *      parameters = {
     *          {"name"="file_id", "dataType"="integer", "required"=true, "description"="documentation.file.file_id"},
     *          {"name"="email", "dataType"="string", "required"=true, "description"="documentation.folder.email"}
     *     },
     *     headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"
     *         }
     *     },
     *     statusCodes = {
     *        200 = "Success",
     *        204 = "File not found",
     *        400 = "Missing parameter",
     *        403 = "Do not have permission to this file",
     *        500 = "Internal server error",
     *    }
     * )
     * @Method("POST")
     * @Route(path="/api/file/send-permalink", name="api_file_send_permalink")
     * @param Request $request
     * @return View
     */
    public function sendMailFileAction(Request $request)
    {
        $resp = new ApiResponse();
        $verifyAccesFile = $this->get("app.peramlink")->verifyAccesFile($request);
        if ($verifyAccesFile !== true) {
            return $verifyAccesFile;
        }
        if (!$request->get("email")) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('Missing mandatory parameters.');
            return new JsonResponse($resp);
        }
        $file = $this->get(FileManager::SERVICE_NAME)->find($request->get("file_id"));
        foreach (array_unique(preg_split("/(;|,)/", $request->get("email"))) as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->sendUrlByMail($email, $request->get("message"), $file);
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
    public function sendUrlByMail($adress, $message, $file)
    {
        $modelEMail = $this->get(EmailAutomatiqueManager::SERVICE_NAME)->findBy(
            ['declenchement' => Constant::SEND_INVITATION],
            ['id' => 'DESC'], 1
        );
        $template = $modelEMail[0]->getTemplate();
        $nameFileFolder = $file->getName();
        $file = $this->get(FileManager::SERVICE_NAME)->getPelmalink($file->getId());
        $file[0]["url"] = ($file[0]["code"]) ? $this->getParameter("host_permalink") . "/" . $file[0]["code"] : "";
        $url = '<a href=" ' . $file[0]["url"] . ' ">' . $nameFileFolder . '</a>';
        $modele = ["__url__", "__username__", "__name_folder__", "__message__"];
        $real = [$url, $this->getUser()->getInfosUser(), $nameFileFolder, $message];
        $template = str_replace($modele, $real, $template);
        $mailer = $this->get("app.mailer");
        return $mailer->sendMailGrid($modelEMail[0]->getObjet(), $adress, $template);
    }

    /**
     * @param $event
     * @param $file
     */
    public function createAction($event, $file)
    {
        $eventAction = $this->getDoctrine()->getRepository(FileLogAction::class)->find($event);
        $this->get(FileLogManager::SERVICE_NAME)->createLog($eventAction, $this->getUser(), $file);
    }
}
