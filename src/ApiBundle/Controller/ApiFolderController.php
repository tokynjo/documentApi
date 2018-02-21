<?php

namespace ApiBundle\Controller;

use AppBundle\Entity\Api\ApiResponse;
use AppBundle\Manager\FileManager;
use AppBundle\Manager\FolderManager;
use AppBundle\Manager\InvitationRequestManager;
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


    /**
     * Get information of folder or file specified
     * @Method("POST")
     * @Route("/api/getInfosUser")
     * @param Request $request
     * @return View
     */
    public function getInfosUser(Request $request)
    {
        $folder_id = $request->get('folder_id');
        $file_id = $request->get('file_id');
        if (!$folder_id and !$file_id) {
            return new JsonResponse(
                [
                    "code" => Response::HTTP_NOT_ACCEPTABLE,
                    "message" => "Missing parameters."
                ]);
        }
        $folderManager = $this->get(FolderManager::SERVICE_NAME);
        $fileManager = $this->get(FileManager::SERVICE_NAME);
        if ($folder_id) {
            $folder = $folderManager->find($folder_id);
            if ($folder == null) {
                $data = [];
                $resp = new ApiResponse();
                $respStatus = Response::HTTP_OK;
                $resp->setCode(Response::HTTP_OK);
                $resp->setData($data);
                return new View($resp, $respStatus);
            }
            $dataFileFolder = $fileManager->getTailleTotal($folder->getId());
            $nbFolder = 0;
            $nbFiles = 0;
            $taille = 0;
            if ($dataFileFolder) {
                $nbFiles = $dataFileFolder["nb_file"];
                $taille = $dataFileFolder["size"];
            }
            $this->recurssive($nbFolder, $taille, $folder, $nbFiles);
            $data = $folderManager->getInfosUser($folder_id);
            $data["nb_files"] = $nbFiles;
            $data["nb_folders"] = $nbFolder;
            $data["taille_folder"] = $taille;
            $resp = new ApiResponse();
            $respStatus = Response::HTTP_OK;
            $resp->setCode(Response::HTTP_OK);
            $resp->setData($data);
            return new View($resp, $respStatus);
        }
        if ($file_id) {
            $data = $fileManager->getInfosUSer($file_id);
            $resp = new ApiResponse();
            $respStatus = Response::HTTP_OK;
            $resp->setCode(Response::HTTP_OK);
            $resp->setData($data);
            return new View($resp, $respStatus);
        }
    }

    /**
     * Get all guests for folder or file selected
     * @Route("/api/getInvites",name="api_get_invité")
     * @Method("POST")
     * @return View
     */
    public function getInvites(Request $request)
    {
        if (!$request->get("id_folder")) {
            return new JsonResponse(
                [
                    "code" => Response::HTTP_NOT_ACCEPTABLE,
                    "message" => "Missing parameters."
                ]);
        }
        $invitationManager = $this->get(InvitationRequestManager::SERVICE_NAME);
        $data = $invitationManager->getInvites($request->get("id_folder"));
    //    $data = [];
        $resp = new ApiResponse();
        $respStatus = Response::HTTP_OK;
        $resp->setCode(Response::HTTP_OK);
        $resp->setData($data);
        return new View($resp, $respStatus);
    }


    /***
     * @param $nbFolder
     * @param $taille
     * @param $dossier
     * @param $nbFiles
     */
    public function recurssive(&$nbFolder, &$taille, $dossier, &$nbFiles)
    {
        foreach ($dossier->getParentFolder() as $child) {
            $fileManager = $this->get(FileManager::SERVICE_NAME);
            $dataFile = $fileManager->getTailleTotal($child->getId());
            if ($dataFile) {
                $taille = $taille + $dataFile["size"];
                $nbFiles += $dataFile["nb_file"];
            }
            $nbFolder++;
            $this->recurssive($nbFolder, $taille, $child, $nbFiles);
        }
    }
}
