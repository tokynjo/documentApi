<?php

namespace ApiBundle\Controller;

use AppBundle\Entity\Api\ApiResponse;
use AppBundle\Manager\CommentManager;
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

class CommentController extends Controller
{
    /**
     * add comment to a file or folder
     * @ApiDoc(
     *      resource=true,
     *      description = "add comment",
     *      parameters = {
     *          {"name"="folder_id", "dataType"="integer", "required"=false, "description"="documentation.folder.id_folder"},
     *          {"name"="file_id", "dataType"="integer", "required"=false, "description"="documentation.file.id_folder"},
     *          {"name"="comment", "dataType"="string", "required"=true, "description"="documentation.file.id_folder"}
     *      },
     *      headers={
     *         {"name"="Authorization", "required"=true, "description"="Authorization token"
     *         }
     *     }
     * )
     * @Route("/api/add-comment",name="api_comment_add")
     * @Method("POST")
     * @param Request $request
     * @return View
     */
    public function addCommentAction(Request $request)
    {
        $folder_id = $request->get("folder_id");
        $file_id = $request->get("file_id");
        $comment = $request->get("comment");

        $resp = $this->get(CommentManager::SERVICE_NAME)->addComment($folder_id, $file_id, $comment );


        if ($request->get("id_folder")) {
            $id_folder = $request->get("id_folder");
            $folderUserManager = $this->get(NewsManager::SERVICE_NAME);
            $data = $folderUserManager->getNewsByFolder($id_folder);
        }
        $resp = new ApiResponse();
        $respStatus = Response::HTTP_OK;
        $resp->setCode(Response::HTTP_OK);
        $resp->setData($data);
        return new View($resp, $respStatus);
    }
}
