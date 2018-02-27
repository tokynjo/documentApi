<?php

namespace AppBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;

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
    public function getNewsByFolder($id_folder){

        return $this->repository->getNewsByFolder($id_folder);
    }

}
