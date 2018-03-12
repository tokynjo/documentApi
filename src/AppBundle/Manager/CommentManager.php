<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Api\ApiResponse;
use Doctrine\ORM\EntityManagerInterface;

class CommentManager extends BaseManager
{
    const SERVICE_NAME = 'app.news_manager';

    public function __construct(EntityManagerInterface $entityManager, $class)
    {
        parent::__construct($entityManager, $class);
    }

    /**
     * @param null $folder_id
     * @param null $file_id
     * @param string $comment
     * @return ApiResponse|JsonResponse
     */
    public function addComment($folder_id = null, $file_id = null, $comment = "")
    {
        $resp = new ApiResponse();
        if (!$comment) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)
                ->setMessage("Missing mandatory parameters comment");

            return $resp;
        }
        if (!$folder_id && !$file_id) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)
                ->setMessage("Missing parameters $folder_id or $file_id");

            return $resp;
        } else {
             die('fsdfds');
        }
        return $resp;
    }
}
