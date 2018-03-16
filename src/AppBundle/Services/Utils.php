<?php

namespace AppBundle\Services;

use AppBundle\Entity\Api\ApiResponse;
use AppBundle\Entity\Constants\Constant;
use AppBundle\Manager\FileManager;
use AppBundle\Manager\FileUserManager;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Utils
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
     * Convert size file
     *
     * @param  $size
     * @return string
     */
    public function getSizeFile($size)
    {
        $size = intval($size);
        if ($size >= 1048576) {
            return number_format(($size / 1048576), 2, '.', ' ') . " Go";
        }
        if ($size >= 1024) {
            return number_format(($size / 1024), 2, '.', ' ') . " Mo";
        } else {
            return $size . " Ko";
        }
    }


}
