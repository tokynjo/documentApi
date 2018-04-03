<?php

namespace ApiBundle\Controller;

use ApiBundle\Manager\UserManager;
use AppBundle\Entity\Api\ApiResponse;
use AppBundle\Entity\Constants\Constant;
use AppBundle\Manager\FileManager;
use AppBundle\Manager\FileUserManager;
use AppBundle\Manager\FolderManager;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ApiFileController extends Controller
{


    /**
     * Rename the given and ensure that the file name is unique <br>
     *
     * @ApiDoc(
     *      resource=true,
     *      description="Rename-file",
     *      parameters = {
     *          {"name"="file_id", "dataType"="integer", "required"=true, "description"="documentation.file.id_file_to_rename"},
     *          {"name"="file_name", "dataType"="string", "required"=true, "description"="documentation.file.new_name"}
     *      },
     *      headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"
     *         }
     *     },
     *      statusCodes = {
     *        200 = "Success",
     *        204 = "Folder not file",
     *        400 = "Missing parameter",
     *        403 = "Do not have permission to this file",
     *        500 = "Internal server error",
     *    }
     * )
     * @Route("/api/rename-file", name="api_rename_file")
     * @Method("POST")
     * @param                     Request $request
     * @return                    View
     */
    public function renameFileAction(Request $request)
    {
        $resp = new ApiResponse();
        $fileName = $request->get("file_name");
        $fileId = $request->get('file_id');
        if (!($fileName && $fileId)) {
            return new JsonResponse($resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('Missing mandatory parameters.'));
        }
        $file = $this->get(FileManager::SERVICE_NAME)->find($fileId);
        if (!$file) {
            return new JsonResponse($resp->setCode(Response::HTTP_NO_CONTENT)->setMessage('File not found.'));
        }
        $resp = $this->get(FileManager::SERVICE_NAME)->renameFile($file, $fileName, $this->getUser());

        return new View($resp, Response::HTTP_OK);
    }

    /**
     * Copy Folder and file in folder <br>
     *
     * @ApiDoc(
     *      resource=true,
     *      description="Copy folder/file",
     *      parameters = {
     *          {"name"="id_destinataire", "dataType"="integer", "required"=true, "description"="documentation.file.id_file_to_rename"},
     *          {"name"="ids_file", "dataType"="string", "required"=true, "description"="documentation.file.ids_file"},
     *          {"name"="ids_folder", "dataType"="string", "required"=true, "description"="documentation.file.ids_folder"}
     *      },
     *      headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"
     *         }
     *     },
     *      statusCodes = {
     *        200 = "Success",
     *        204 = "Folder not Folder",
     *        400 = "Missing parameter",
     *        403 = "Do not have permission to this folder",
     *        500 = "Internal server error",
     *    }
     * )
     * @Method("POST")
     * @Route(path="/api/copy", name="api_copy_data")
     * @param                   Request $request
     * @return                  View|JsonResponse
     */
    public function copyAction(Request $request)
    {
        $resp = new ApiResponse();
        if (!$request->get("id_destinataire") || (!$request->get("ids_folder") && !$request->get("ids_file"))) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('Missing mandatory parameters.');

            return new View($resp, Response::HTTP_BAD_REQUEST);
        }
        if (!$folder = $this->get(FolderManager::SERVICE_NAME)->find($request->get("id_destinataire"))) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('Folder not found.');

            return new View($resp, Response::HTTP_BAD_REQUEST);
        }
        $data = $this->get(FolderManager::SERVICE_NAME)
            ->copyData($folder, $request->get("ids_folder"), $request->get("ids_file"), $this->getUser());
        $resp->setData($data);

        return new View($resp, Response::HTTP_OK);
    }

    /**
     * Delete file<br>
     *
     * @ApiDoc(
     *      resource=true,
     *      description="Delete file",
     *      parameters = {
     *          {"name"="file_id", "dataType"="integer", "required"=false, "description"="documentation.file.id_file"}
     *      },
     *      headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"
     *         }
     *     },
     *      statusCodes = {
     *        200 = "Success",
     *        204 = "File not found",
     *        400 = "Missing mandatory parameters",
     *        403 = "Do not have permission to the file",
     *        500 = "Internal server error",
     *    }
     * )
     * @Route("/api/delete-file", name="api_delete_file")
     * @Method("POST")
     * @param                     Request $request
     * @return                    View
     */
    public function deleteFileAction(Request $request)
    {
        $resp = new ApiResponse();
        if (!$request->get('file_id')) {
            return new View($resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('Missing mandatory parameters.'), Response::HTTP_OK);
        }
        $data = $this->get(FileManager::SERVICE_NAME)->hasRighToDelete($request->get('file_id'), $this->getUser());
        if (!$data) {
            return new View($resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('Do not have permission to this file'), Response::HTTP_OK);
        }
        $file = $this->get(FileManager::SERVICE_NAME)->find($request->get('file_id'));
        if (!$file) {
            return new View($resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('File not found.'), Response::HTTP_OK);
        }
        $fileUser = $this->get(FileUserManager::SERVICE_NAME)->findNotExpired($request->get('file_id'));
        if ($fileUser) {
            return new View($resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('This file is shared.'), Response::HTTP_OK);
        }
        $this->get(FileManager::SERVICE_NAME)->deleteFile($file);
        $this->get('app.openstack.objectstore')->deleteFile($file, $this->getUser());

        return new View($resp, Response::HTTP_OK);
    }

    /**
     * Get list of users invited to a file<br>
     *
     * @ApiDoc(
     *      resource=true,
     *      description="Users of a file",
     *      parameters = {
     *          {"name"="folder_id", "dataType"="integer", "required"=false, "description"="documentation.file.id_file"}
     *      },
     *      headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"
     *         }
     *     },
     *      statusCodes = {
     *        200 = "Success",
     *        204 = "File not found",
     *        400 = "Missing mandatory parameters",
     *        500 = "Internal server error"
     *    }
     * )
     * @Route("/api/file-users", name="api_file_users")
     * @Method("POST")
     * @param                    Request $request
     * @return                   View
     */
    public function getUsersFileAction(Request $request)
    {
        $resp = new ApiResponse();
        $fileId = $request->get('file_id');
        if (!$fileId) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('Missing mandatory parameters.');

            return new View($resp, Response::HTTP_OK);
        }
        $file = $this->get(FileManager::SERVICE_NAME)->find($fileId);
        if (!$file) {
            $resp->setCode(Response::HTTP_NO_CONTENT)->setMessage('File not found.');

            return new View($resp, Response::HTTP_OK);
        }
        $users = $this->get(FileManager::SERVICE_NAME)->getUsersToFile($file);
        $resp->setData($users);

        return new View($resp, Response::HTTP_OK);
    }
    /**
     * Assign a file to a new owner.<br>
     *
     * @ApiDoc(
     *      resource=true,
     *      description="reassign a file to owner",
     *      parameters = {
     *          {"name"="file_id", "dataType"="integer", "required"=true, "description"="documentation.file.id_file"},
     *          {"name"="user_id", "dataType"="integer", "required"=true, "description"="documentation.folder.id_user"}
     *      },
     *      headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"}
     *     },
     *      statusCodes = {
     *        200 = "Success",
     *        204 = "File not found | User not found",
     *        400 = "Missing mandatory parameters",
     *        403 = "Do not have permission to the file",
     *        500 = "Internal server error"
     *    }
     * )
     * @Route("/api/setting-file-owner", name="api_file_users_change")
     * @Method("POST")
     * @param                            Request $request
     * @return                           View
     */
    public function settingFileOwnerAction(Request $request)
    {
        $resp = $this->get(FileManager::SERVICE_NAME)->setOwenFileAction($request, $this->getUser());

        return $resp;
    }


}
