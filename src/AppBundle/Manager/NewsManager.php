<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Constants\Constant;
use AppBundle\Entity\News;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\File;

class NewsManager extends BaseManager
{
    const SERVICE_NAME = 'app.news_manager';

    public function __construct(EntityManagerInterface $entityManager, $class)
    {
        parent::__construct($entityManager, $class);
    }

    /**
     * @param $id_folder
     * @return mixed
     */
    public function getNewsByFolder($id_folder)
    {

        return $this->repository->getNewsByFolder($id_folder);
    }

    /**
     * @param $id_folder
     * @return mixed
     */
    public function getNewsDetails($id_folder)
    {

        $news = $this->findBy(
            ['folder' => $id_folder, 'parent'=>null],
            ['createdAt'=> 'desc']
        );
        //var_dump(count($news)); die;
        $iterator = new \ArrayIterator($news);
        $actualities = [];
        while ($iterator->valid()) {

            $actualities[] = $this->getNewsDataStructure($iterator->current());
            $iterator->next();
        }
        print_r($actualities); die;
    }


    protected function getNewsDataStructure(News $news)
    {
        $actuality = [];
        $actuality['id'] = $news->getId();
        $actuality['type'] = $news->getType()->getLabel();
        $actuality['project_id'] = $news->getProject() ? $news->getProject()->getId() : null;
        $actuality['project_name'] = $news->getProject() ? $news->getProject()->getLibelle() : null;
        $actuality['user_id'] = $news->getUser()->getId();
        $actuality['user_first_name'] = $news->getUser()->getFirstname();
        $actuality['user_last_name'] = $news->getUser()->getLastname();
        $actuality['user_avatar'] = $news->getUser()->getAvatar();
        $actuality['creation_date'] = $news->getCreatedAt()->format("Y-m-d H:i:s");
        if ($news->getType()->getId()== Constant::NEWS_TYPE_UPLOAD_FILE) {
            if (isset($news->getData()['file']) && count($news->getData()['file'] > 0)){
                foreach ($news->getData()['file'] as $fileId) {
                    if ($file = $this->entityManager->find(File::class, $fileId)) {
                        $actuality['file_id'] = $file->getId();
                        $actuality['file_name'] = $file->getName();
                        $actuality['file_size'] = $file->getFileSize();
                    }
                }
            }
        }
        if (count($news->getComments()) > 0) {
            $actuality['comment_id'] = $news->getComments()[0]->getId();
            $actuality['comment_message'] = $news->getComments()[0]->getMessage();
        }
        $actuality['children'] = [];

        if(count($news->getChildren())) {
            foreach($news->getChildren() as $n ) {
                $actuality['children'][] = $this->getNewsDataStructure($n);
            }
        }
        return $actuality;
    }
}
