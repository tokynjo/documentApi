<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/api", name="prospect_api_controller")
 */
class ApiUserController extends FOSRestController
{
    /**
     * display user current
     * @Method("GET")
     * @Route("/getuser")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getUserAction()
    {
        $user = $this->getUser();
        $view = $this->view($user);
        return $this->handleView($view);
    }

}
