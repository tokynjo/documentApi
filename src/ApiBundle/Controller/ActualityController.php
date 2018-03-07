<?php

namespace ApiBundle\Controller;

use AppBundle\Entity\Api\ApiResponse;
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

class ActualityController extends Controller
{
    /**
     * Get all actualities of a given folder
     * @ApiDoc(
     *      resource=true,
     *      description = "get folder/file actuality",
     *      parameters = {
     *          {"name"="id_folder", "dataType"="integer", "required"=false, "description"="folder id"}
     *      },
     *      headers={
     *         {"name"="Authorization", "required"=true, "description"="Authorization token"
     *         }
     *     }
     * )
     * @Route("/api/getActualites",name="api_get_actualites")
     * @Method("POST")
     * @return View
     */
    public function getActualites(Request $request)
    {
        if (!$request->get("id_folder")) {
            return new JsonResponse(
                [
                    "code" => Response::HTTP_BAD_REQUEST,
                    "message" => "Missing parameters id_folder."
                ]
            );
        }
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
