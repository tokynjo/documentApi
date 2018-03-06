<?php

namespace ApiBundle\Controller;

use AppBundle\Entity\Api\ApiResponse;
use AppBundle\Event\FolderEvent;
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getStructureAction(Request $request)
    {
        $folder_id = $request->get('folder_id');
        $folderManager = $this->get(FolderManager::SERVICE_NAME);
        $fileManager = $this->get(FileManager::SERVICE_NAME);
        $resp = new ApiResponse();
        $respStatus = Response::HTTP_OK;
        $user = $this->getUser();
        if (!$folder_id) {
            $data = $folderManager->getStructure($user);
            $data["interne"]["files"] = $fileManager->getStructureInterne($user);
            $data["externe"]["files"] = $fileManager->getStructureExterne($user);
        } else {
            $data = $folderManager->getStructure($user, $folder_id);
            $data["interne"]["files"] = $fileManager->getStructureInterne($user, $folder_id);
        }
        $resp->setCode(Response::HTTP_OK);
        $resp->setData($data);
        return new View($resp, $respStatus);
    }


    /**
     * Get folder/file information details : total size, folder content ...
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
            $data["taille_folder"] = $this->getSizeFile($taille);
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
     * @return View
     */
    public function getInvites(Request $request)
    {
        if (!$request->get("id_folder") && !$request->get("id_file")) {
            return new JsonResponse(
                [
                    "code" => Response::HTTP_BAD_REQUEST,
                    "message" => "Missing parameters."
                ]);
        }
        if ($request->get("id_folder")) {
            $folderUserManager = $this->get(FolderUserManager::SERVICE_NAME);
            $data = $folderUserManager->getInvites($request->get("id_folder"));
        }
        if ($request->get("id_file")) {
            $folderUserManager = $this->get(FileUserManager::SERVICE_NAME);
            $data = $folderUserManager->getInvites($request->get("id_file"));
        }
        $resp = new ApiResponse();
        $respStatus = Response::HTTP_OK;
        $resp->setCode(Response::HTTP_OK);
        $resp->setData($data);
        return new View($resp, $respStatus);
    }

    /**
     * Lock folder for owner and/or manager <br>
     * When locked the folder never appears in the shared document except for this owner
     *
     * @ApiDoc(
     *      resource=true,
     *      description="Lock folder",
     *      parameters = {
     *          {"name"="folder_id", "dataType"="integer", "required"=true, "description"="documentation.folder.id_folder_to_lock"}
     *      },
     *      headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"
     *         }
     *     },
     *      statusCodes = {
     *        200 = "Success",
     *        202 = "Folder already unlocked",
     *        204 = "Folder not found",
     *        400 = "Missing parameter",
     *        403 = "Do not have permission to this folder"
     *    }
     * )
     * @Route("/api/lock-folder", name="api_lock_folder")
     * @Method("POST")
     * @param Request $request
     * @return View
     */
    public function lockFolderAction (Request $request)
    {
        $resp = new ApiResponse();

        $folder_id = (int)$request->get('folder_id');
        if (!$folder_id) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)
                ->setMessage('Missing parameters.');
            return new JsonResponse($resp);
        }
        $folder = $this->get(FolderManager::SERVICE_NAME)->find($folder_id);
        if (!$folder) {

            $resp->setCode(Response::HTTP_NO_CONTENT)
                ->setMessage('Resources not found.');
            return new View($resp, Response::HTTP_NO_CONTENT);
        }


        $result = $this->get(FolderManager::SERVICE_NAME)->lockFolder($folder, $this->getUser());
        switch ($result) {
            case Response::HTTP_OK :
                //save log
                $folderEvent = new FolderEvent($folder);
                $oDispatcher = $this->container->get("event_dispatcher");
                $oDispatcher->dispatch($folderEvent::FOLDER_ON_LOCK, $folderEvent);
                $resp->setCode(Response::HTTP_OK);
                break;
            case Response::HTTP_ACCEPTED :
                $resp->setCode(Response::HTTP_ACCEPTED) ;
                $resp->setMessage('Folder already locked');
                break;
            case Response::HTTP_FORBIDDEN :
                $resp->setCode(Response::HTTP_FORBIDDEN);
                $resp->setMessage('Do not have permission to this folder');
                break;
        }

        return new View($resp, Response::HTTP_OK);
    }

    /**
     * unlock folder for owner and/or manager <br>
     * When folder unlocked, this will appears in the shared document
     *
     * @ApiDoc(
     *      resource=true,
     *      description="Unlock folder",
     *      parameters = {
     *          {"name"="folder_id", "dataType"="integer", "required"=true, "description"="documentation.folder.id_folder_to_unlock"}
     *      },
     *      headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"
     *         }
     *     },
     *      statusCodes = {
     *        200 = "Success",
     *        202 = "Folder already unlocked",
     *        204 = "Folder not found",
     *        400 = "Missing parameter",
     *        403 = "Do not have permission to this folder"
     *    }
     * )
     * @Route("/api/unlock-folder", name="api_unlock_folder")
     * @Method("POST")
     * @param Request $request
     * @return View
     */
    public function unlockFolderAction (Request $request)
    {
        $resp = new ApiResponse();
        $folder_id = (int)$request->get('folder_id');
        if (!$folder_id) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)
                ->setMessage('Missing parameters.');
            return new JsonResponse($resp);
        }
        $folder = $this->get(FolderManager::SERVICE_NAME)->find($folder_id);
        if (!$folder) {

            $resp->setCode(Response::HTTP_NO_CONTENT)
                ->setMessage('Resources not found.');
            return new View($resp, Response::HTTP_NO_CONTENT);
        }
        $result = $this->get(FolderManager::SERVICE_NAME)->unlockFolder($folder, $this->getUser());
        switch ($result) {
            case Response::HTTP_OK :
                //save log
                $folderEvent = new FolderEvent($folder);
                $oDispatcher = $this->container->get("event_dispatcher");
                $oDispatcher->dispatch($folderEvent::FOLDER_ON_UNLOCK, $folderEvent);
                $resp->setCode(Response::HTTP_OK);
                break;
            case Response::HTTP_ACCEPTED :
                $resp->setCode(Response::HTTP_ACCEPTED) ;
                $resp->setMessage('Folder already unlocked');
                break;
            case Response::HTTP_FORBIDDEN :
                $resp->setCode(Response::HTTP_FORBIDDEN);
                $resp->setMessage('Do not have permission to this folder');
                break;
        }
        return new View($resp, Response::HTTP_OK);
    }

    /**
     * Create folder to the given folder parent and ensure that the folder name is unique <br>
     *
     *
     * @ApiDoc(
     *      resource=true,
     *      description="Create folder",
     *      parameters = {
     *          {"name"="folder_id", "dataType"="integer", "required"=false, "description"="documentation.folder.id_folder_parent"},
     *          {"name"="folder_name", "dataType"="string", "required"=true, "description"="documentation.folder.new_folder_name"}
     *      },
     *      headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"
     *         }
     *     },
     *      statusCodes = {
     *        200 = "Success",
     *        400 = "Missing parameter",
     *        403 = "Do not have permission to this folder",
     *        500 = "Internal server error"
     *    }
     * )
     * @Route("/api/create-folder", name="api_create_folder")
     * @Method("POST")
     * @param Request $request
     * @return View
     */
    public function createFolderAction (Request $request)
    {
        $resp = new ApiResponse();
        $folder_name = $request->get('folder_name');
        $folder_id = $request->get('folder_id');
        if (!$folder_name) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)
                ->setMessage('Missing mandatory parameters.');
            return new JsonResponse($resp);
        }
        if(!$this->get(FolderManager::SERVICE_NAME)->hasRightToCreateFolder($folder_id, $this->getUser())) {
            $resp->setCode(Response::HTTP_FORBIDDEN)
                ->setMessage('Do not have permission to this folder');
            return new View($resp, Response::HTTP_BAD_REQUEST);
        }
        if (!$this->get(FolderManager::SERVICE_NAME)->isFolderNameAvailable($folder_id, $folder_name)) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)
                ->setMessage('Folder name already exists');
            return new View($resp, Response::HTTP_BAD_REQUEST);
        }

        $folder = $this->get(FolderManager::SERVICE_NAME)->createFolder($folder_id, $folder_name, $this->getUser());

        //save log
        $folderEvent = new FolderEvent($folder);
        $oDispatcher = $this->container->get("event_dispatcher");
        $oDispatcher->dispatch($folderEvent::FOLDER_ON_CREATION, $folderEvent);
                $resp->setCode(Response::HTTP_OK);

        return new View($resp, Response::HTTP_OK);
    }


    /**
     * Rename the given and ensure that the folder name is unique in the parent folder <br>
     *
     * @ApiDoc(
     *      resource=true,
     *      description="Rename-folder",
     *      parameters = {
     *          {"name"="folder_id", "dataType"="integer", "required"=true, "description"="documentation.folder.id_folder_to_rename"},
     *          {"name"="folder_name", "dataType"="string", "required"=true, "description"="documentation.folder.new_name"}
     *      },
     *      headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"
     *         }
     *     },
     *      statusCodes = {
     *        200 = "Success",
     *        204 = "Folder not found",
     *        400 = "Missing parameter",
     *        403 = "Do not have permission to this folder",
     *        500 = "Internal server error",
     *    }
     * )
     * @Route("/api/rename-folder", name="api_rename_folder")
     * @Method("POST")
     * @param Request $request
     * @return View
     */
    public function renameFolderAction (Request $request)
    {
        $resp = new ApiResponse();
        $folder_name = $request->get('folder_name');
        $folder_id = $request->get('folder_id');
        if (!$folder_name) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)
                ->setMessage('Missing mandatory parameters.');
            return new JsonResponse($resp);
        }

        $folder = $this->get(FolderManager::SERVICE_NAME)->find($folder_id);
        if (!$folder) {
            $resp->setCode(Response::HTTP_NO_CONTENT)
                ->setMessage('Folder not found.');
            return new JsonResponse($resp);
        }
        if(!$this->get(FolderManager::SERVICE_NAME)->hasRightToCreateFolder($folder_id, $this->getUser())) {
            $resp->setCode(Response::HTTP_FORBIDDEN);
            $resp->setMessage('Do not have permission to this folder');
            return new View($resp, Response::HTTP_NO_CONTENT);
        }
        $parentFolderId = $folder->getParentFolder() ? $folder->getParentFolder()->getId() : null;
        if (!$this->get(FolderManager::SERVICE_NAME)->isFolderNameAvailable($parentFolderId, $folder_name)) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)
                ->setMessage('Folder name already exists');
            return new View($resp, Response::HTTP_BAD_REQUEST);
        }

        $folder = $this->get(FolderManager::SERVICE_NAME)->renameFolder($folder, $folder_name, $this->getUser());
        //save log
        $folderEvent = new FolderEvent($folder);
        $oDispatcher = $this->container->get("event_dispatcher");
        $oDispatcher->dispatch($folderEvent::FOLDER_ON_RENAME, $folderEvent);
        $resp->setCode(Response::HTTP_OK);

        return new View($resp, Response::HTTP_OK);
    }

    /**
     * Delete folder<br>
     *
     * @ApiDoc(
     *      resource=true,
     *      description="Delete folder",
     *      parameters = {
     *          {"name"="folder_id", "dataType"="integer", "required"=false, "description"="documentation.folder.id_folder"}
     *      },
     *      headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"
     *         }
     *     },
     *      statusCodes = {
     *        200 = "Success",
     *        204 = "Folder not found",
     *        400 = "Missing mandatory parameters",
     *        403 = "Do not have permission to the folder",
     *        500 = "Internal server error",
     *    }
     * )
     * @Route("/api/delete-folder", name="api_delete_folder")
     * @Method("POST")
     * @param Request $request
     * @return View
     */
    public function deleteFolderAction (Request $request)
    {
        $resp = new ApiResponse();
        $folder_id = $request->get('folder_id');
        if (!$folder_id) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)
                ->setMessage('Missing mandatory parameters.');
            return new JsonResponse($resp);
        }
        $folder = $this->get(FolderManager::SERVICE_NAME)->find($folder_id);
        if (!$folder) {
            $resp->setCode(Response::HTTP_NO_CONTENT)
                ->setMessage('Folder not found.');
            return new JsonResponse($resp);
        }
        if(!$this->get(FolderManager::SERVICE_NAME)->hasRightToCreateFolder($folder_id, $this->getUser())) {
            $resp->setCode(Response::HTTP_FORBIDDEN);
            $resp->setMessage('Do not have permission to the folder');
            return new JsonResponse($resp, Response::HTTP_FORBIDDEN);
        }
        $folder = $this->get(FolderManager::SERVICE_NAME)->deleteFolder($folder, $this->getUser());
        $data = [];
        $data['folder_id'] = $folder->getId();
        $resp->setData($data);
        return new View($resp, Response::HTTP_OK);
    }

    /**
     * Get list of users invited to a folder<br>
     *
     *
     * @ApiDoc(
     *      resource=true,
     *      description="Users of a folder",
     *      parameters = {
     *          {"name"="folder_id", "dataType"="integer", "required"=false, "description"="documentation.folder.id_folder"}
     *      },
     *      headers={
     *         {"name"="Authorization", "required"=true, "description"="documentation.authorization_token"
     *         }
     *     },
     *      statusCodes = {
     *        200 = "Success",
     *        204 = "Folder not found",
     *        400 = "Missing mandatory parameters",
     *        500 = "Internal server error"
     *    }
     * )
     * @Route("/api/folder-users", name="api_folder_users")
     * @Method("POST")
     * @param Request $request
     * @return View
     */
    public function getUsersFolderAction (Request $request)
    {
        $resp = new ApiResponse();
        $folder_id = $request->get('folder_id');
        if (!$folder_id) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)
                ->setMessage('Missing mandatory parameters.');
            
            return new JsonResponse($resp);
        }
        $folder = $this->get(FolderManager::SERVICE_NAME)->find($folder_id);
        if (!$folder) {
            $resp->setCode(Response::HTTP_NO_CONTENT)
                ->setMessage('Folder not found.');

            return new JsonResponse($resp);
        }

        $users = $this->get(FolderManager::SERVICE_NAME)->getUsersToFolder($folder_id);
        $resp->setData($users);

        return new View($resp, Response::HTTP_OK);
    }

    /***
     * @param $nbFolder
     * @param $taille
     * @param $dossier
     * @param $nbFiles
     */
    public function recurssive (&$nbFolder, &$taille, $dossier, &$nbFiles)
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

    /**
     * Convert size file
     * @param $size
     * @return string
     */
    public function getSizeFile($size)
    {
        $size = intval($size);
        if ($size >= 1048576) {
            return number_format(($size / 1048576),2,'.',' ') . " Go";
        }
        if ($size >= 1024) {
            return number_format(($size / 1024),2,'.',' ') . " Mo";
        } else {
            return $size . " Ko";
        }
    }
}
