<?php

namespace ApiBundle\Controller;

use AppBundle\Entity\Api\ApiResponse;
use AppBundle\Manager\FolderManager;
use AppBundle\Manager\UserManager;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ApiFolderController extends Controller
{
    /**
     * Lock folder for owner and/or manager <br>
     * When locked the folder never appears in the shared document except for this owner
     *
     * @ApiDoc(
     *      resource=true,
     *      description="Lock folder",
     *      parameters = {
     *          {"name"="folder_id", "dataType"="integer", "required"=true, "description"="documentation.folder.id_folder_to_lock"}
     *      },
     *      headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"
     *         }
     *     },
     *      statusCodes = {
     *        200 = "Success",
     *        202 = "Folder already unlocked",
     *        204 = "Folder not found",
     *        400 = "Missing parameter",
     *        403 = "Do not have permission to this folder"
     *    }
     * )
     * @Route("/api/lock-folder", name="api_lock_folder")
     * @Method("POST")
     * @param                     Request $request
     * @return                    View
     */
    public function lockFolderAction(Request $request)
    {
        $resp = new ApiResponse();
        $folder_id = (int)$request->get('folder_id');
        if (!$folder_id) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)
                ->setMessage('Missing parameters.');
            return new View($resp, Response::HTTP_BAD_REQUEST);
        }
        $folder = $this->get(FolderManager::SERVICE_NAME)->find($folder_id);
        if (!$folder) {
            $resp->setCode(Response::HTTP_NO_CONTENT)
                ->setMessage('Resources not found.');
            return new View($resp, Response::HTTP_NO_CONTENT);
        }
        $resp =$this->get(FolderManager::SERVICE_NAME)->lockFolder($folder, $this->getUser());

        return new View($resp, Response::HTTP_OK);
    }

    /**
     * unlock folder for owner and/or manager <br>
     * When folder unlocked, this will appears in the shared document
     *
     * @ApiDoc(
     *      resource=true,
     *      description="Unlock folder",
     *      parameters = {
     *          {"name"="folder_id", "dataType"="integer", "required"=true, "description"="documentation.folder.id_folder_to_unlock"}
     *      },
     *      headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"
     *         }
     *     },
     *      statusCodes = {
     *        200 = "Success",
     *        202 = "Folder already unlocked",
     *        204 = "Folder not found",
     *        400 = "Missing parameter",
     *        403 = "Do not have permission to this folder"
     *    }
     * )
     * @Route("/api/unlock-folder", name="api_unlock_folder")
     * @Method("POST")
     * @param                       Request $request
     * @return                      View
     */
    public function unlockFolderAction(Request $request)
    {
        $resp = new ApiResponse();
        $folder_id = (int)$request->get('folder_id');
        if (!$folder_id) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)
                ->setMessage('Missing parameters.');
            return new JsonResponse($resp);
        }
        $folder = $this->get(FolderManager::SERVICE_NAME)->find($folder_id);
        if (!$folder) {
            $resp->setCode(Response::HTTP_NO_CONTENT)
                ->setMessage('Resources not found.');
            return new JsonResponse($resp);
        }
        $resp = $this->get(FolderManager::SERVICE_NAME)->unlockFolder($folder, $this->getUser());

        return new View($resp, Response::HTTP_OK);
    }

    /**
     * Create folder to the given folder parent and ensure that the folder name is unique <br>
     *
     * @ApiDoc(
     *      resource=true,
     *      description="Create folder",
     *      parameters = {
     *          {"name"="folder_id", "dataType"="integer", "required"=false, "description"="documentation.folder.id_folder_parent"},
     *          {"name"="folder_name", "dataType"="string", "required"=true, "description"="documentation.folder.new_folder_name"}
     *      },
     *      headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"
     *         }
     *     },
     *      statusCodes = {
     *        200 = "Success",
     *        400 = "Missing parameter",
     *        403 = "Do not have permission to this folder",
     *        500 = "Internal server error"
     *    }
     * )
     * @Route("/api/create-folder", name="api_create_folder")
     * @Method("POST")
     * @param                       Request $request
     * @return                      View
     */
    public function createFolderAction(Request $request)
    {
        $resp = new ApiResponse();
        $folder_name = $request->get('folder_name');
        $folder_id = $request->get('folder_id');
        if (!$folder_name) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('Missing mandatory parameters.');
            return new View($resp, Response::HTTP_OK);
        }
        if (!$this->get(FolderManager::SERVICE_NAME)->hasRightToCreateFolder($folder_id, $this->getUser())) {
            $resp->setCode(Response::HTTP_FORBIDDEN)->setMessage('Do not have permission to this folder');
            return new View($resp, Response::HTTP_OK);
        }
        if (!$this->get(FolderManager::SERVICE_NAME)->isFolderNameAvailable($folder_id, $folder_name)) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('Folder name already exists');
            return new View($resp, Response::HTTP_OK);
        }
        $this->get(FolderManager::SERVICE_NAME)->createFolder($folder_id, $folder_name, $this->getUser());
        $resp->setCode(Response::HTTP_OK);

        return new View($resp, Response::HTTP_OK);
    }


    /**
     * Rename the given and ensure that the folder name is unique in the parent folder <br>
     *
     * @ApiDoc(
     *      resource=true,
     *      description="Rename-folder",
     *      parameters = {
     *          {"name"="folder_id", "dataType"="integer", "required"=true, "description"="documentation.folder.id_folder_to_rename"},
     *          {"name"="folder_name", "dataType"="string", "required"=true, "description"="documentation.folder.new_name"}
     *      },
     *      headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"
     *         }
     *     },
     *      statusCodes = {
     *        200 = "Success",
     *        204 = "Folder not found",
     *        400 = "Missing parameter",
     *        403 = "Do not have permission to this folder",
     *        500 = "Internal server error",
     *    }
     * )
     * @Route("/api/rename-folder", name="api_rename_folder")
     * @Method("POST")
     * @param                       Request $request
     * @return                      View
     */
    public function renameFolderAction(Request $request)
    {
        $resp = new ApiResponse();
        $folder_name = $request->get('folder_name');
        $folder_id = $request->get('folder_id');
        if (!$folder_name) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('Missing mandatory parameters.');
            return new View($resp, Response::HTTP_OK);
        }
        $folder = $this->get(FolderManager::SERVICE_NAME)->find($folder_id);
        if (!$folder) {
            $resp->setCode(Response::HTTP_NO_CONTENT)->setMessage('Folder not found.');
            return new View($resp, Response::HTTP_OK);
        }
        $resp = $this->get(FolderManager::SERVICE_NAME)->renameFolder($folder, $folder_name, $this->getUser());

        return new View($resp, Response::HTTP_OK);
    }

    /**
     * Delete folder<br>
     *
     * @ApiDoc(
     *      resource=true,
     *      description="Delete folder",
     *      parameters = {
     *          {"name"="folder_id", "dataType"="integer", "required"=false, "description"="documentation.folder.id_folder"}
     *      },
     *      headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"
     *         }
     *     },
     *      statusCodes = {
     *        200 = "Success",
     *        204 = "Folder not found",
     *        400 = "Missing mandatory parameters",
     *        403 = "Do not have permission to the folder",
     *        500 = "Internal server error",
     *    }
     * )
     * @Route("/api/delete-folder", name="api_delete_folder")
     * @Method("POST")
     * @param                       Request $request
     * @return                      View
     */
    public function deleteFolderAction(Request $request)
    {
        $resp = new ApiResponse();
        $folder_id = $request->get('folder_id');
        if (!$folder_id) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('Missing mandatory parameters.');
            return new View($resp, Response::HTTP_OK);
        }
        $resp = $this->get(FolderManager::SERVICE_NAME)->deleteFolder($folder_id, $this->getUser());

        return new View($resp, Response::HTTP_OK);
    }

    /**
     * Get list of users invited to a folder<br>
     *
     * @ApiDoc(
     *      resource=true,
     *      description="Users of a folder",
     *      parameters = {
     *          {"name"="folder_id", "dataType"="integer", "required"=false, "description"="documentation.folder.id_folder"}
     *      },
     *      headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"
     *         }
     *     },
     *      statusCodes = {
     *        200 = "Success",
     *        204 = "Folder not found",
     *        400 = "Missing mandatory parameters",
     *        500 = "Internal server error"
     *    }
     * )
     * @Route("/api/folder-users", name="api_folder_users")
     * @Method("POST")
     * @param                      Request $request
     * @return                     View
     */
    public function getUsersFolderAction(Request $request)
    {
        $resp = new ApiResponse();
        $folder_id = $request->get('folder_id');
        if (!$folder_id) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)
                ->setMessage('Missing mandatory parameters.');
            return new View($resp, Response::HTTP_OK);
        }
        $folder = $this->get(FolderManager::SERVICE_NAME)->find($folder_id);
        if (!$folder) {
            $resp->setCode(Response::HTTP_NO_CONTENT)
                ->setMessage('Folder not found.');
            return new View($resp, Response::HTTP_OK);
        }

        $users = $this->get(FolderManager::SERVICE_NAME)->getUsersToFolder($folder_id);
        $resp->setData($users);

        return new View($resp, Response::HTTP_OK);
    }


    /**
     * Assign a folder to a new owner.<br>
     * Recursively with this sub-folders and children files
     *
     * @ApiDoc(
     *      resource=true,
     *      description="reassign a folder to owner",
     *      parameters = {
     *          {"name"="folder_id", "dataType"="integer", "required"=true, "description"="documentation.folder.id_folder"}
     *      },
     *      headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"}
     *     },
     *      statusCodes = {
     *        200 = "Success",
     *        204 = "Folder not found | User not found",
     *        400 = "Missing mandatory parameters",
     *        403 = "Do not have permission to the folder",
     *        500 = "Internal server error"
     *    }
     * )
     * @Route("/api/setting-folder-owner", name="api_folder_users")
     * @Method("POST")
     * @param                              Request $request
     * @return                             View
     */
    public function settingFolderOwnerAction(Request $request)
    {
        $resp = new ApiResponse();
        $folder_id = $request->get('folder_id');
        $user_id = $request->get('user_id');
        if (!$folder_id || !$user_id) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('Missing mandatory parameters.');
            return new View($resp, Response::HTTP_OK);
        }
        $folder = $this->get(FolderManager::SERVICE_NAME)->find($folder_id);
        if (!$folder) {
            $resp->setCode(Response::HTTP_NO_CONTENT)->setMessage('Folder not found.');
            return new View($resp, Response::HTTP_OK);
        }
        if (!$folder->getUser()->getId() === $this->getUser()->getId()) {
            $resp->setCode(Response::HTTP_FORBIDDEN)->setMessage('Do not have permission to the folder');
            return new View($resp, Response::HTTP_OK);
        }
        $user = $this->get(UserManager::SERVICE_NAME)->find($user_id);
        if (!$user) {
            $resp->setCode(Response::HTTP_NO_CONTENT)->setMessage('User not found');
            return new View($resp, Response::HTTP_OK);
        }
        $this->get(FolderManager::SERVICE_NAME)->setFolderOwner($folder, $user);
        $resp->setData([]);

        return new View($resp, Response::HTTP_OK);
    }

    /**
     * Crypt folder
     *
     * @ApiDoc(
     *      resource=true,
     *      description="Crypt folder",
     *      parameters = {
     *          {"name"="folder_id", "dataType"="integer", "required"=true, "description"="documentation.folder.id_folder"}
     *      },
     *      headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"}
     *     },
     *      statusCodes = {
     *        200 = "Success",
     *        204 = "Folder not found",
     *        400 = "Missing mandatory parameters",
     *        403 = "Do not have permission to the folder",
     *        500 = "Internal server error"
     *    }
     * )
     * @Method("POST")
     * @Route(path="/api/crypt-folder" ,name="api_crypt_folder")
     * @return                         View
     */
    public function cryptAction(Request $request)
    {
        $resp = new ApiResponse();
        if (!$request->get("folder_id")) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('Missing mandatory parameters.');
            return new View($resp, Response::HTTP_OK);
        }
        $folder = $this->get(FolderManager::SERVICE_NAME)->find($request->get("folder_id"));
        if (!$folder) {
            $resp->setCode(Response::HTTP_NO_CONTENT)->setMessage('Folder not found.');

            return new View($resp, Response::HTTP_OK);
        }
        $code["cryptKey"] = $this->get("app.peramlink")->generate();
        $this->get(FolderManager::SERVICE_NAME)->crypt($folder, $code["cryptKey"]);
        $resp->setData($code);
        return new View($resp, Response::HTTP_OK);
    }

    /**
     * @ApiDoc(
     *      resource=true,
     *      description="Decrypt folder",
     *      parameters = {
     *          {"name"="folder_id", "dataType"="integer", "required"=true, "description"="documentation.folder.id_folder"}
     *      },
     *      headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"}
     *     },
     *      statusCodes = {
     *        200 = "Success",
     *        204 = "Folder not found",
     *        400 = "Missing mandatory parameters",
     *        403 = "Do not have permission to the folder",
     *        500 = "Internal server error"
     *    }
     * )
     * @Method("POST")
     * @Route(path="/api/decrypt-folder" ,name="api_decrypt_folder")
     * @return View
     */
    public function deCryptAction(Request $request)
    {
        $resp = new ApiResponse();
        if (!$request->get("folder_id")) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('Missing mandatory parameters.');
            return new View($resp, Response::HTTP_OK);
        }
        $folder = $this->get(FolderManager::SERVICE_NAME)->find($request->get("folder_id"));
        if (!$folder) {
            $resp->setCode(Response::HTTP_NO_CONTENT)->setMessage('Folder not found.');
            return new View($resp, Response::HTTP_OK);
        }
        $this->get(FolderManager::SERVICE_NAME)->decrypt($folder);
        return new View($resp, Response::HTTP_OK);
    }

    /**
     * @ApiDoc(
     *      resource=true,
     *      description="send url and code crypt folder",
     *      parameters = {
     *          {"name"="folder_id", "dataType"="integer", "required"=true, "description"="documentation.folder.id_folder"},
     *          {"name"="mails", "dataType"="string", "required"=false, "description"="documentation.comment.to_notify"},
     *          {"name"="numeros", "dataType"="string", "required"=false, "description"="documentation.folder.numeros"}
     *      },
     *      headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"}
     *     },
     *      statusCodes = {
     *        200 = "Success",
     *        204 = "Folder not found| User not found",
     *        400 = "Missing mandatory parameters",
     *        403 = "Do not have permission to the folder",
     *        500 = "Internal server error"
     *    }
     * )
     * @Method("POST")
     * @Route(path="/api/send-crypt-folder" ,name="api_send_crypt_folder")
     * @return View
     */
    public function sendCryptAction(Request $request)
    {
        $resp = new ApiResponse();
        if (!$request->get("folder_id")) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('Missing mandatory parameters.');
            return new JsonResponse($resp);
        }
        $folder = $this->get(FolderManager::SERVICE_NAME)->find($request->get("folder_id"));
        if (!$folder) {
            $resp->setCode(Response::HTTP_NO_CONTENT)->setMessage('Folder not found.');
            return new JsonResponse($resp);
        }
        $mails = array_unique(preg_split("/(;|,)/", $request->get("mails")));
        foreach ($mails as $mail) {
            $this->get("app.mailer")->sendUrlByMail($mail, $request->get("message"), $folder);
            $data["mail_receivers"][] = $mail;
        }
        if($request->get("numeros")){
            $data["sms"] = $this->get("app.sms")->send($request->get("numeros"), $folder);
        }
        $resp->setData($data);
        return new View($resp, Response::HTTP_OK);
    }

}
