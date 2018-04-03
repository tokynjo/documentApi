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
     * body param is json_encode of $files <br>
     * ---------------------------------------
     * [ <br>
     * &nbsp;&nbsp;&nbsp;{ <br>
     * &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"name":"document.docx", //document name <br>
     * &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"content":"base64_encode(file_get_contents("filepath/api.docx")" //file content base64_encoded <br>
     * &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"overwrite_id": "58"  // file id where overwrite a file <br>
     * &nbsp;&nbsp;&nbsp;}, <br>
     * &nbsp;&nbsp;&nbsp;{ <br>
     *  &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;"name":"json.txt", <br>
     *  &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;"content":"..." <br>
     * &nbsp;&nbsp;&nbsp;}, <br>
     * .... <br>
     * ] <br>
     *
     * @ApiDoc(
     *      resource=true,
     *      description="Upload files",
     *      parameters = {
     *          {"name"="folder_id", "dataType"="integer", "required"=false, "description"="documentation.folder.id_folder_parent"}
     *      },
     *      headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"
     *         }
     *     },
     *      statusCodes = {
     *        200 = "Success",
     *        404 = "File not found",
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

    /** @Route("/api/file-details", name="api_file_details")
     * @Method("POST")
     * @param                     Request $request
     * @return                    View
     */
    public function getFileDetails (Request $request)
    {
        $resp = $this->get(FileManager::SERVICE_NAME)->getFileDetails($request->get('file_id'));

        return new View($resp, Response::HTTP_OK);
    }

}
