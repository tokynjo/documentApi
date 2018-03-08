<?php

namespace AppBundle\Controller;

use AppBundle\Manager\ClientManager;
use OAuth2\OAuth2ServerException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class OAuthController extends Controller
{
    /**
     * Get Authorization token to the API.<br>
     * This token is mandatory to access all api methods.
     * @ApiDoc(
     *      resource = true,
     *      description = "Get authorization token to access the api",
     *      parameters = {
     *          {"name"="username", "dataType"="string", "required"=true, "description"="user email"},
     *          {"name"="password", "dataType"="string", "required"=true, "description"="user password"}
     *
     *      }
     * )
     * @Method("POST")
     * @Route("/getToken", name="app_oauth_server_token")
     */
    public function getTokenAction(Request $request)
    {
        $last_client = $this->get(ClientManager::SERVICE_NAME)->findBy([], array('id' => 'DESC'), 1);
        if (isset($last_client[0])) {
            if ($request->get("refresh_token")) {
                $request->request->set('refresh_token', $request->get("refresh_token"));
                $grant_type = "refresh_token";
            } else {
                $grant_type = $this->getParameter("grant_type");
            }
            $last_client = $last_client[0];
            $client_id = $last_client->getId() . "_" . $last_client->getRandomId();
            $client_secret = $last_client->getSecret();
            $request->request->set('grant_type', $grant_type);
            $request->request->set('client_id', $client_id);
            $request->request->set('client_secret', $client_secret);
            $request->request->set('username', $request->get("username"));
            $request->request->set('password', $request->get("password"));
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

    /**
     * @Method("POST")
     * @Route("/token/refresh", name="app_oauth_refresh_token")
     */
    public function getRefreshTokenAction(Request $request)
    {
        $last_client = $this->get(ClientManager::SERVICE_NAME)->findBy([], array('id' => 'DESC'), 1);
        if (isset($last_client[0])) {
            $grant_type = "refresh_token";
            $last_client = $last_client[0];
            $client_id = $last_client->getId() . "_" . $last_client->getRandomId();
            $request->request->set('grant_type', $grant_type);
            $request->request->set('client_id', $client_id);
            $request->request->set('refresh_token', $request->get("refresh_token"));
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
