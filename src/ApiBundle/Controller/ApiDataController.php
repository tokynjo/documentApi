<?php
namespace ApiBundle\Controller;

use AppBundle\Manager\FolderManager;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ApiDataController extends Controller
{
    /**
     * Move data<br>
     * Move given folders/files to a given destination folder
     *
     * @ApiDoc(
     *      resource=true,
     *      description="Move data",
     *      parameters = {
     *          {"name"="parent_id", "dataType"="integer", "required"=true, "description"="documentation.folder.id_folder_parent"},
     *          {"name"="folder_ids", "dataType"="string", "required"=false, "description"="documentation.folder.ids_folders"},
     *          {"name"="file_ids", "dataType"="string", "required"=false, "description"="documentation.file.file_ids"}
     *      },
     *      headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"}
     *     },
     *      statusCodes = {
     *        200 = "Success",
     *        400 = "At least one of folder_ids and file_ids is mandatory",
     *        403 = "Do not have permission on folder(s)/file(s)",
     *        500 = "Internal server error",
     *    }
     * )
     * @Route("/api/move-data", name="api_data_move")
     * @Method("POST")
     * @param                   Request $request
     * @return                  View
     */
    public function moveDataAction(Request $request)
    {
        $parent_id = $request->get('parent_id');
        $folder_ids = $request->get('folder_ids');
        $file_ids = $request->get('file_ids');
        $resp = $this->get(FolderManager::SERVICE_NAME)->moveData($parent_id, $folder_ids, $file_ids);

        return new View($resp, Response::HTTP_OK);
    }
}
