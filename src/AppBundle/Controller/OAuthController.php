<?php

namespace AppBundle\Controller;

use AppBundle\Manager\ClientManager;
use OAuth2\OAuth2ServerException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OAuthController extends Controller
{
    /**
     * @Route("/oauth/v2/token")
     */
    public function getTokenAction(Request $request)
    {
        $last_client = $this->get(ClientManager::SERVICE_NAME)->findBy([], array('id' => 'DESC'), 1);
        if (isset($last_client[0])) {
            $grant_type = $this->getParameter("grant_type");
            $last_client = $last_client[0];
            $client_id = $last_client->getId() . "_" . $last_client->getRandomId();
            $client_secret = $last_client->getSecret();
            $request->query->set('grant_type', $grant_type);
            $request->query->set('client_id', $client_id);
            $request->query->set('client_secret', $client_secret);
            $server = $this->get('fos_oauth_server.server');
            try {
                return $server->grantAccessToken($request);
            } catch (OAuth2ServerException $e) {
                return $e->getHttpResponse();
            }
        } else {
            return new JsonResponse(['error' => 'client not found']);
        }

    }

}
