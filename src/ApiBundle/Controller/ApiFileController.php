<?php

namespace ApiBundle\Controller;

use AppBundle\Entity\Api\ApiResponse;
use AppBundle\Manager\FileManager;
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
        $file_name = $request->get("file_name");
        $file_id = $request->get('file_id');
        if (!$file_name) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('Missing mandatory parameters.');
            return new JsonResponse($resp);
        }
        $file = $this->get(FileManager::SERVICE_NAME)->find($file_id);
        if (!$file) {
            $resp->setCode(Response::HTTP_NO_CONTENT)->setMessage('File not found.');
            return new JsonResponse($resp);
        }
        $resp = $this->get(FileManager::SERVICE_NAME)->renameFile($file, $file_name, $this->getUser());
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
}
