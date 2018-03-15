<?php

namespace ApiBundle\Controller;


use AppBundle\Manager\CommentManager;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class CommentController extends Controller
{
    /**
     * add comment to a file or folder
     * @ApiDoc(
     *      resource=true,
     *      description = "add comment",
     *      parameters = {
     *          {"name"="folder_id", "dataType"="integer", "required"=false, "description"="documentation.folder.id_folder"},
     *          {"name"="file_id", "dataType"="integer", "required"=false, "description"="documentation.file.file_id"},
     *          {"name"="comment", "dataType"="string", "required"=true, "description"="documentation.comment.comment"},
     *          {"name"="parent_id", "dataType"="string", "required"=true, "description"="documentation.comment.parent_id"},
     *          {"name"="to_notify", "dataType"="string", "required"=false, "description"="documentation.comment.to_notify"}
     *      },
     *      headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"
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
        $parent_id = $request->get("parent_id");
        $comment = $request->get("comment");
        $to_notify = $request->get("to_notify");

        $resp = $this->get(CommentManager::SERVICE_NAME)->addComment(
            $folder_id,
            $file_id,
            $parent_id,
            $comment,
            $to_notify,
            $this->getUser()
        );

        return new View($resp, Response::HTTP_OK);
    }
}
