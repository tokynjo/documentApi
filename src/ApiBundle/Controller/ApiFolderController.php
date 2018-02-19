<?php

namespace ApiBundle\Controller;

use AppBundle\Entity\Api\ApiResponse;
use AppBundle\Manager\FileManager;
use AppBundle\Manager\FolderManager;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use FOS\UserBundle\Event\GetResponseUserEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseNullableUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiFolderController extends Controller
{
    /**
     * get structure of user
     * @Method("POST")
     * @Route("/api/getstructure")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getStructureAction(Request $request)
    {
        $folderManager = $this->get(FolderManager::SERVICE_NAME);
        $fileManager = $this->get(FileManager::SERVICE_NAME);
        $resp = new ApiResponse();
        $respStatus = Response::HTTP_CREATED;
        $user = $this->getUser();
        $data = $folderManager->getStructure($user);
        $data["interne"]["files"] = $fileManager->getStructureInterne($user);
        $data["externe"]["files"] = $fileManager->getStructureExterne($user);
        $resp->setCode(Response::HTTP_OK);
        $resp->setData($data);
        return new View($resp, $respStatus);
    }


}
