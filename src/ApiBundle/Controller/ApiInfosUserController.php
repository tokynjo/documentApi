<?php

namespace ApiBundle\Controller;

use AppBundle\Entity\Api\ApiResponse;
use AppBundle\Entity\Constants\Constant;
use AppBundle\Entity\Folder;
use AppBundle\Event\FolderEvent;
use AppBundle\Manager\FileManager;
use AppBundle\Manager\FileUserManager;
use AppBundle\Manager\FolderManager;
use AppBundle\Manager\FolderUserManager;
use AppBundle\Manager\UserManager;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ApiInfosUserController extends Controller
{
    /**
     * Get user's folders structure and shared folders structure.<br>
     * List all folders and file in the first child level.
     *
     * @ApiDoc(
     *      resource = true,
     *      description = "Get structure folders",
     *      headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"}
     *      },
     *      parameters = {
     *          {"name"="folder_id", "dataType"="integer", "required"=false, "description"="documentation.folder.id_folder"}
     *      },
     *      statusCodes={
     *         200="Success"
     *     }
     * )
     * @Method("POST")
     * @Route("/api/getstructure")
     * @return                     \Symfony\Component\HttpFoundation\Response
     */
    public function getStructureAction(Request $request)
    {
        $folder_id = $request->get('folder_id');
        $folderManager = $this->get(FolderManager::SERVICE_NAME);
        $fileManager = $this->get(FileManager::SERVICE_NAME);
        $resp = new ApiResponse();
        $user = $this->getUser();
        if (!$folder_id) {
            $data = $folderManager->getStructure($user);
            $data["interne"]["files"] = $fileManager->getStructureInterne($user);
            $data["externe"]["files"] = $fileManager->getStructureExterne($user);
        } else {
            $data = $folderManager->getStructure($user, $folder_id);
            $data["interne"]["files"] = $fileManager->getStructureInterne($user, $folder_id);
        }
        $resp->setData($data);
        return new View($resp, Response::HTTP_OK);
    }

    /**
     * get internal folder structure recursively<br>
     * return folders content only without files list
     *
     * @ApiDoc(
     *      resource = true,
     *      description = "Get internal structure folders",
     *      headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"}
     *      },
     *      parameters = {
     *          {"name"="folder_id", "dataType"="integer", "required"=false, "description"="documentation.folder.id_folder"}
     *      },
     *      statusCodes={
     *         200="Success"
     *     }
     * )
     * @param Request $request
     * @Method("POST")
     * @Route("/api/get-internal-structure")
     * @return View
     */
    public function getInternalStructureAction(Request $request)
    {
        $folder_id = $request->get('folder_id');
        $folderManager = $this->get(FolderManager::SERVICE_NAME);
        $user = $this->getUser();
        $resp = $folderManager->getInternalStructure($user, $folder_id);

        return new View($resp, Response::HTTP_OK);
    }

    /**
     * Get folder/file information details : total size, folder content ...
     *
     * @ApiDoc(
     *      resource=true,
     *      description="Get information of folder or file specified",
     *      parameters = {
     *          {"name"="folder_id", "dataType"="integer", "required"=false, "description"="documentation.folder.id_folder"},
     *          {"name"="file_id", "dataType"="integer", "required"=false, "description"="documentation.file.file_id"}
     *      },
     *      headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"
     *         }
     *     }
     * )
     * @Method("POST")
     * @Route("/api/getInfosUser")
     * @param                      Request $request
     * @return                     View
     */
    public function getInfosUserAction(Request $request)
    {
        if (!$request->get('folder_id') && !$request->get('file_id')) {
            return new JsonResponse(["code" => Response::HTTP_NOT_ACCEPTABLE, "message" => "Missing parameters."]);
        }
        $resp = new ApiResponse();
        if ($request->get('folder_id')) {
            $folder = $this->get(FolderManager::SERVICE_NAME)->find($request->get('folder_id'));
            if (!$folder) {
                $resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('Folder not found.');
                return new JsonResponse($resp);
            }
            $dataFileFolder = $this->get(FileManager::SERVICE_NAME)->getTailleTotal($request->get('folder_id'));
            $nbFolder = 0;
            $nbFiles = 0;
            $taille = 0;
            if ($dataFileFolder) {
                $nbFiles = $dataFileFolder["nb_file"];
                $taille = $dataFileFolder["size"];
            }
            $this->get(FolderManager::SERVICE_NAME)->recurssive($nbFolder, $taille, $folder, $nbFiles);
            $data = $this->get(FolderManager::SERVICE_NAME)->getInfosUser($request->get('folder_id'));
            $data["nb_files"] = $nbFiles;
            $data["nb_folders"] = $nbFolder;
            $data["taille_folder"] = $this->get("app.utils")->getSizeFile($taille);
            $resp->setData($data);
            return new View($resp, Response::HTTP_OK);
        }
        if ($request->get('file_id')) {
            $data = $this->get(FileManager::SERVICE_NAME)->getInfosUSer($request->get('file_id'));
            $resp->setData($data);
            return new View($resp, Response::HTTP_OK);
        }
    }

    /**
     * Get list of all invited users to a folder/file by one of folder id or file id.<br>
     * One of the 2 parameters is required
     *
     * @ApiDoc(
     *      resource=true,
     *      description="Get invited users on folder/file",
     *      parameters = {
     *          {"name"="id_folder", "dataType"="integer", "required"=false, "description"="documentation.folder.id_folder"},
     *          {"name"="id_file", "dataType"="integer", "required"=false, "description"="documentation.file.file_id"}
     *      },
     *      headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"
     *         }
     *     }
     * )
     * @Route("/api/getInvites",name="api_get_invites")
     * @Method("POST")
     * @return                                          View
     */
    public function getInvitesAction(Request $request)
    {
        if (!$request->get("id_folder") && !$request->get("id_file")) {
            return new JsonResponse(
                ["code" => Response::HTTP_BAD_REQUEST, "message" => "Missing parameters."]
            );
        }
        if ($request->get("id_folder")) {
            $folderUserManager = $this->get(FolderUserManager::SERVICE_NAME);
            $data = $folderUserManager->getInvites($request->get("id_folder"));
        } elseif ($request->get("id_file")) {
            $folderUserManager = $this->get(FileUserManager::SERVICE_NAME);
            $data = $folderUserManager->getInvites($request->get("id_file"));
        }
        $resp = new ApiResponse();
        $resp->setData($data);
        return new View($resp, Response::HTTP_OK);
    }
}
