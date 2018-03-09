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
use AppBundle\Manager\FolderLogManager;
use AppBundle\Manager\FolderManager;
use AppBundle\Manager\FolderUserManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class PermalinkController extends Controller
{
    /**
     * Get permalink
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
        $folderManager = $this->get(FolderManager::SERVICE_NAME);
        $folder = $folderManager->find($request->get("folder_id"));
        $resp->setData(
            [
                "id" => $request->get("folder_id"),
                "url" => $folder->getPermalink(),
                "shared" => $folder->getShare(),
                "protected" => ($folder->getSharePassword()) ? true : false
            ]
        );
        return new View($resp, Response::HTTP_OK);
    }

    /**
     * Get permalink of file
     * @ApiDoc(
     *      resource=true,
     *      description="Generate permalink-file",
     *      parameters = {
     *          {"name"="file_id", "dataType"="integer", "required"=true, "description"="documentation.file.id_file"},
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
        $verifyAccesFolder = $this->verifyAccesFile($request);
        if ($verifyAccesFolder !== true) {
            return $verifyAccesFolder;
        }
        $fileManager = $this->get(FileManager::SERVICE_NAME);
        $file = $fileManager->find($request->get("file_id"));
        $resp->setData(
            [
                "id" => $request->get("file_id"),
                "url" => $file->getPermalink(),
                "shared" => $file->getShare(),
                "protected" => ($file->getSharePassword()) ? true : false
            ]
        );
        return new View($resp, Response::HTTP_OK);
    }

    /**
     * @ApiDoc(
     *      resource=true,
     *      description="Share permalink of folder",
     *      parameters = {
     *          {"name"="folder_id", "dataType"="integer", "required"=true, "description"="documentation.folder.id_folder"},
     *          {"name"="share", "dataType"="integer", "required"=true, "description"="documentation.folder.share"}
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
     * @Route(path="/api/folder/share-permalink", name="api_share_permalink_folder")
     * @param Request $request
     * @return View
     */
    public function sharePermalinkFolderAction(Request $request)
    {
        $resp = new ApiResponse();
        $verifyAccesFolder = $this->verifyAccesFolder($request);
        if ($verifyAccesFolder !== true) {
            return $verifyAccesFolder;
        }
        if ($request->get("active") == null) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('Missing mandatory parameters.');
            return new JsonResponse($resp);
        }
        $folderManager = $this->get(FolderManager::SERVICE_NAME);
        $folder = $folderManager->find($request->get("folder_id"));

        $folder->setShare($request->get("share"));
        $folderManager->saveAndFlush($folder);
        $resp->setData(
            [
                "url" => $folder->getPermalink(),
                "shared" => $folder->getShare(),
                "protected" => ($folder->getSharePassword()) ? true : false
            ]);
        return new View($resp, Response::HTTP_OK);
    }

    /**
     * @ApiDoc(
     *      resource=true,
     *      description="Share permalink of file",
     *      parameters = {
     *          {"name"="folder_id", "dataType"="integer", "required"=true, "description"="documentation.folder.id_file"},
     *          {"name"="share", "dataType"="integer", "required"=true, "description"="documentation.file.share"}
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
     * @Route(path="/api/file/share-permalink", name="api_share_permalink_file")
     * @param Request $request
     * @return View
     */
    public function sharePermalinkFileAction(Request $request)
    {
        $resp = new ApiResponse();
        $verifyAccesFile = $this->verifyAccesFile($request);
        if ($verifyAccesFile !== true) {
            return $verifyAccesFile;
        }
        if ($request->get("share") == null) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('Missing mandatory parameters.');
            return new JsonResponse($resp);
        }
        $fileManager = $this->get(FileManager::SERVICE_NAME);
        $file = $fileManager->find($request->get("file_id"));
        $file->setShare($request->get("share"));
        $fileManager->saveAndFlush($file);
        if ($request->get("share") == Constant::SHARED) {
            $this->createAction(Constant::FILE_LOG_ACTION_SHARE, $file);
        } else {
            $this->createAction(Constant::FILE_LOG_ACTION_NOT_SHARE, $file);
        }
        $resp->setData(
            [
                "url" => $file->getPermalink(),
                "shared" => $file->getShare(),
                "protected" => ($file->getSharePassword()) ? 1 : 0
            ]);
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
        $resp->setData(
            [
                "url" => $folder->getPermalink(),
                "shared" => $folder->getShare(),
                "protected" => ($folder->getSharePassword()) ? true : false
            ]);
        return new View($resp, Response::HTTP_OK);
    }

    /**
     * Protect with password
     * @ApiDoc(
     *      resource=true,
     *      description="Protect with a password a permalink of file",
     *      parameters = {
     *          {"name"="file_id", "dataType"="integer", "required"=true, "description"="documentation.folder.id_file"},
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
     * @param Request $request
     * @return View
     */
    public function setPassWordFileAction(Request $request)
    {
        $resp = new ApiResponse();
        $verifyAccesFile = $this->verifyAccesFile($request);
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
        $resp->setData(
            [
                "url" => $file->getPermalink(),
                "shared" => $file->getShare(),
                "protected" => ($file->getSharePassword()) ? true : false
            ]);
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
        $folder->setPermalink($this->getParameter("host_preprod") . "/" . $this->get("app.peramlink")->generate());
        $folderManager->saveAndFlush($folder);
        $resp->setData(
            [
                "url" => $folder->getPermalink(),
                "shared" => $folder->getShare(),
                "protected" => ($folder->getSharePassword()) ? 1 : 0
            ]);
        return new View($resp, Response::HTTP_OK);
    }

    /**
     * @ApiDoc(
     *      resource=true,
     *      description="Regenerate permalink of file",
     *      parameters = {
     *          {"name"="file_id", "dataType"="integer", "required"=true, "description"="documentation.file.id_file"},
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
        $verifyAccesFile = $this->verifyAccesFile($request);
        if ($verifyAccesFile !== true) {
            return $verifyAccesFile;
        }
        $fileManager = $this->get(FileManager::SERVICE_NAME);
        $file = $fileManager->find($request->get("file_id"));
        $file->setPermalink($this->getParameter("host_preprod") . "/" . $this->get("app.peramlink")->generate());
        $fileManager->saveAndFlush($file);
        $resp->setData(
            [
                "url" => $file->getPermalink(),
                "shared" => $file->getShare(),
                "protected" => ($file->getSharePassword()) ? 1 : 0
            ]);
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
        $folderManager = $this->get(FolderManager::SERVICE_NAME);
        $folder = $folderManager->find($request->get("folder_id"));
        $emails = array_unique(preg_split("/(;|,)/", $request->get("email")));
        foreach ($emails as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->sendUrlByMail($email, $request->get("message"), $folder, null, $folder->getPermalink());
                $data['email_share_success'][] = $email;
            } else {
                $data['email_share_fail'][] = $email;
            }
        }
        $resp->setData($data);
        return new View($resp, Response::HTTP_OK);
    }

    /**
     * @ApiDoc(
     *      resource=true,
     *      description="Send by email a permalink of file",
     *      parameters = {
     *          {"name"="file_id", "dataType"="integer", "required"=true, "description"="documentation.file.id_file"},
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
        $verifyAccesFile = $this->verifyAccesFile($request);
        if ($verifyAccesFile !== true) {
            return $verifyAccesFile;
        }
        if (!$request->get("email")) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('Missing mandatory parameters.');
            return new JsonResponse($resp);
        }
        $fileManager = $this->get(FileManager::SERVICE_NAME);
        $file = $fileManager->find($request->get("file_id"));
        $emails = array_unique(preg_split("/(;|,)/", $request->get("email")));
        foreach ($emails as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->sendUrlByMail($email, $request->get("message"), null, $file, $file->getPermalink());
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
    public function sendUrlByMail($adress, $message, $folder, $file, $url)
    {
        $modelEMail = $this->get(EmailAutomatiqueManager::SERVICE_NAME)->findBy(
            ['declenchement' => Constant::SEND_INVITATION],
            ['id' => 'DESC'], 1);
        $template = $modelEMail[0]->getTemplate();
        $nameFileFolder = ($folder) ? $folder->getName() : $file->getName();
        $url = '<a href=" ' . $url . ' ">' . $nameFileFolder . '</a>';
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
     * @param $request
     * @return bool|JsonResponse
     */
    public function verifyAccesFile($request)
    {
        $resp = new ApiResponse();
        if (!$request->get("file_id")) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('Missing mandatory parameters.');
            return new JsonResponse($resp);
        }
        $fileManager = $this->get(FileManager::SERVICE_NAME);
        $file = $fileManager->find($request->get("file_id"));
        if (!$file) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('File not found.');
            return new JsonResponse($resp);
        }
        $tab_right = [Constant::RIGHT_MANAGER, Constant::RIGHT_CONTRIBUTOR];
        if (!$this->get(FolderUserManager::SERVICE_NAME)->getRightUser($file, $this->getUser(), $tab_right)) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('Do not have permission to this file');
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
        if ($folder instanceof Folder) {
            $eventAction = $this->getDoctrine()->getRepository(FolderLogAction::class)->find($event);
            $this->get(FolderLogManager::SERVICE_NAME)->createLog($eventAction, $this->getUser(), $folder);
        } else {
            $eventAction = $this->getDoctrine()->getRepository(FileLogAction::class)->find($event);
            $this->get(FileLogManager::SERVICE_NAME)->createLog($eventAction, $this->getUser(), $folder);
        }
    }
}
