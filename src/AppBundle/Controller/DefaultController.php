<?php

namespace AppBundle\Controller;

use AppBundle\Manager\ClientManager;
use OAuth2\OAuth2ServerException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Method("GET")
     * @Route("/", name="homepage")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return new Response(
            $this->renderView('home/index.html.twig', [
                'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            ]),
            200,
            []
        );
    }

    /**
     * @Method("GET")
     * @Route( path="/share/{permalink}/folder/{id}/",name="app_getby_code_permalien")
     * @return View
     */
    public function getByCode(Request $request, $id, $permalink)
    {
        return new Response(
            $this->renderView('home/index.html.twig', [
                'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            ]),
            200,
            []
        );
    }
}
