<?php

namespace AppBundle\Services;

use Psr\Container\ContainerInterface;

class Permalink
{

    public function __construct(ContainerInterface $container)
    {
    }

    /**
     * @return string
     */
    public function generate(){
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $string = '';
            for ($i = 0; $i < 8; $i++) {
                $string .= $characters[mt_rand(0, strlen($characters) - 1)];
            }
            return $string;
    }
}
