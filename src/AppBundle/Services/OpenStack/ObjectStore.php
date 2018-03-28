<?php

namespace AppBundle\Services\OpenStack;


use ApiBundle\Entity\User;
use GuzzleHttp\Psr7\Stream;
use Symfony\Component\HttpFoundation\File\File;

class ObjectStore extends OpenStack
{
    const SERVICE_NAME = 'app.openstack.objectstore';

    public function __construct($os_options = [])
    {
        parent::__construct($os_options);
    }


    public function sendFile($container_name = "wedrop_data", $file = null)
    {
        $options = [
            'name'    => $file->id.'-'.uniqid().'.'.pathinfo($file->name,PATHINFO_EXTENSION),
            'content' =>base64_decode($file->content)
        ];

        $object = $this->openStack->objectStoreV1()
            ->getContainer($container_name)
            ->createObject($options);

        return $object;
    }


    public function createContainer(User $user) {
        $service = $this->openStack->objectStoreV1();
        $container = $service->createContainer([
            'name' => $user->getId().'-'.uniqid()
        ]);
        return $container;
    }

}
