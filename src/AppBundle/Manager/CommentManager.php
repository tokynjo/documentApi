<?php

namespace AppBundle\Manager;

use ApiBundle\Entity\User;
use AppBundle\Entity\Api\ApiResponse;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Constants\Constant;
use AppBundle\Entity\File;
use AppBundle\Entity\Folder;
use AppBundle\Entity\News;
use AppBundle\Entity\NewsType;
use AppBundle\Event\CommentEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;

class CommentManager extends BaseManager
{
    const SERVICE_NAME = 'app.comment_manager';

    protected $dispatcher = null;

    public function __construct(
        EntityManagerInterface $entityManager,
        $class,
        EventDispatcherInterface $eventDispatcher
    ) {
        parent::__construct($entityManager, $class);
        $this->dispatcher = $eventDispatcher;

    }

    /**
     * @param null   $folder_id
     * @param null   $file_id
     * @param null   $parent_id
     * @param string $comment
     * @param string $to_notify
     * @param User   $user
     * @return ApiResponse
     */

    public function addComment(
        $folder_id = null,
        $file_id = null,
        $parent_id = null,
        $comment = "",
        $to_notify = "",
        User $user
    ) {
        $resp = new ApiResponse();
        if (!$comment) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)
                ->setMessage("Missing mandatory parameters comment");

            return $resp;
        }
        $com = new Comment();
        $newsType = $this->entityManager->find(NewsType::class, Constant::NEWS_TYPE_COMMENT);
        $actuality = new News();
        $actuality->setType($newsType)
            ->setUser($user)
            ->setProject(null)
            ->setParent(null);
        $actualityData = [
            'comment' => $comment,
            'folder_id'=> $folder_id,
            'file_id'=>$file_id
        ];
        $actuality->setData($actualityData);


        if (!$folder_id && !$file_id) {
            $resp->setCode(Response::HTTP_BAD_REQUEST)
                ->setMessage("Missing parameters folder_id or file_id");

            return $resp;
        } elseif ($folder_id) {
            $folder = $this->entityManager->find(Folder::class, $folder_id);
            if ($folder) {
                //save actuality
                $actuality->setFolder($folder);
                $actuality = $this->saveAndFlush($actuality);
                // add comment to folder
                $com->setMessage($comment)
                    ->setUser($user)
                    ->setFolder($folder)
                    ->setFile(null)
                    ->setNews($actuality);
                $this->saveAndFlush($com);
            } else {
                $resp->setCode(Response::HTTP_NO_CONTENT)
                    ->setMessage("Folder not found");

                return $resp;
            }

        } elseif ($file_id) {
            //save actuality
            $actuality = $this->saveAndFlush($actuality);
            //add comment to file
            $file = $this->entityManager->find(File::class, $file_id);
            if ($file) {
                // add comment to file
                $com->setMessage($comment)
                    ->setUser($user)
                    ->setFolder(null)
                    ->setFile($file)
                    ->setNews($actuality);
                $this->saveAndFlush($com);
            } else {
                $resp->setCode(Response::HTTP_NO_CONTENT)
                    ->setMessage("File not found");

                return $resp;
            }
        }
        /**
         * sending email notification, to selected user
         */
        $commentEvent = new CommentEvent($com);
        $commentEvent->setToNotify($to_notify);
        $this->dispatcher->dispatch($commentEvent::COMMENT_ON_CREATE, $commentEvent);

        $data = [];
        $data['comment_id'] = $com->getId();
        $resp->setData($data);

        return $resp;
    }
}
