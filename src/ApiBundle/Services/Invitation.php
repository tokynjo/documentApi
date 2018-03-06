<?php

namespace ApiBundle\Services;

use Psr\Container\ContainerInterface;



class Invitation
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }



}
