<?php

namespace ApiBundle\Controller;

use AppBundle\Entity\Api\ApiResponse;
use AppBundle\Manager\FileManager;
use AppBundle\Services\OpenStack\ObjectStore;
use AppBundle\Services\OpenStack\OpenStack;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class UploadController extends Controller
{


    /**
     * upload file into server <br>
     *
     * @ApiDoc(
     *      resource=true,
     *      description="Rename-file",
     *      parameters = {
     *          {"name"="folder_id", "dataType"="integer", "required"=false, "description"="documentation.folder.id_folder_parent"},
     *          {"name"="files", "dataType"="[]", "required"=true, "description"="documentation.upload.files"}
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
     * @Route("/api/file-upload", name="api_file_upload")
     * @Method("POST")
     * @param                     Request $request
     * @return                    View
     */
    public function uploadFileAction(Request $request)
    {
        $folder_id = $request->get('target_folder_id');
        $files = json_decode($request->getContent());

        $resp = $this->get(FileManager::SERVICE_NAME)->createFiles($folder_id, $files);


        return new View($resp, Response::HTTP_OK);
    }

}
