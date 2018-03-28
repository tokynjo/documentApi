<?php

namespace AppBundle\Services;

use AppBundle\Entity\Api\ApiResponse;
use AppBundle\Entity\Constants\Constant;
use AppBundle\Manager\FileManager;
use AppBundle\Manager\FileUserManager;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Permalink
{

    private $container;

    /**
     * Permalink constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return string
     */
    public function generate()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = '';
        for ($i = 0; $i < 8; $i++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        return $string;
    }

    /**
     * @param Request $request
     *
     * @return bool|JsonResponse
     */
    public function verifyAccesFile(Request $request)
    {
        $resp = new ApiResponse();
        if (!$request->get("file_id")) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('Missing mandatory parameters.');

            return new JsonResponse($resp);
        }
        $fileManager = $this->container->get(FileManager::SERVICE_NAME);
        $file = $fileManager->find($request->get("file_id"));
        if (!$file) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('File not found.');

            return new JsonResponse($resp);
        }
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $tabRight = [Constant::RIGHT_MANAGER, Constant::RIGHT_CONTRIBUTOR];
        if (!$this->container->get(FileUserManager::SERVICE_NAME)->getRightUser($file, $user, $tabRight)) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('Do not have permission to this file');

            return new JsonResponse($resp);
        }

        return true;
    }
}
