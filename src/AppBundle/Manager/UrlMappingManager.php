<?php

namespace AppBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;

class UrlMappingManager extends BaseManager
{
    const SERVICE_NAME = 'app.url_mapping_manager';

    public function __construct(EntityManagerInterface $entityManager, $class)
    {
        parent::__construct($entityManager, $class);
    }

    public function create($code, $url)
    {
        $class = $this->class;
        $urlMapping = new $class();
        $urlMapping->setCode($code);
        $urlMapping->setUrl($url);
        $this->saveAndFlush($urlMapping);
        return $urlMapping;
    }

}
