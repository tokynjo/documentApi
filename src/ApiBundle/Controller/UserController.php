<?php

namespace ApiBundle\Controller;

use AppBundle\Entity\Api\ApiResponse;
use AppBundle\Entity\Constants\Constant;
use AppBundle\Manager\EmailAutomatiqueManager;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\UserBundle\Event\GetResponseUserEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseNullableUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class UserController extends FOSRestController
{
    /**
     * @ApiDoc(
     *      resource=true,
     *      description="Send mail to reset password user",
     *      parameters = {
     *          {"name"="email", "dataType"="string", "required"=true, "description"="documentation.folder.email"},
     *      },
     *      headers={
     *     },
     *      statusCodes = {
     *        200 = "Success",
     *        204 = "User not found",
     *        400 = "Missing mandatory parameters",
     *        500 = "Internal server error"
     *    }
     * )
     * @Method("POST")
     * @Route(path="/reset/password/send-mail" ,name="api_send_reset_user")
     * @return JsonResponse
     */
    public function resetPasswordRequestAction(Request $request)
    {
        $resp = new ApiResponse();
        $user = $this->get('fos_user.user_manager')->findUserByEmail($request->get('email'));
        if (!$request->get('email')) {
            return new JsonResponse($resp->setCode(Response::HTTP_BAD_REQUEST)->setMessage('Missing parameters.'));
        }
        if (null === $user) {
            return new JsonResponse($resp->setCode(Response::HTTP_NO_CONTENT)->setMessage('User not found.'));
        }
        if (null === $user->getConfirmationToken()) {
            $tokenGenerator = $this->get('fos_user.util.token_generator');
            $user->setConfirmationToken($tokenGenerator->generateToken());
        }
       //this->get('fos_user.mailer')->sendResettingEmailMessage($user);
        $user->setPasswordRequestedAt(new \DateTime());
        $this->get('fos_user.user_manager')->updateUser($user);
        $resp->setData(["token" => $user->getConfirmationToken()]);
        $this->sendMailReset($request->get('email'), $user->getConfirmationToken());
        return new JsonResponse($resp);
    }

    /**
     * Reset password
     *
     * @Method("POST")
     * @Route("/profile/api-password-reset/{token}", name="modify_password")
     * @param                                        Request $request
     * @param                                        $token
     * @return                                       RedirectResponse|Response
     */
    public function resetAction(Request $request, $token)
    {
        $firstPassword = $request->query->get("first_password");
        $secondPassword = $request->query->get("second_password");
        if ($firstPassword === null || $secondPassword === null) {
            return new JsonResponse(
                [
                    'error' => Response::HTTP_CONTINUE,
                    'message' => 'Missing parameter'
                ]
            );
        }
        if ($firstPassword !== $secondPassword) {
            return new JsonResponse(
                [
                    'error' => Response::HTTP_CONTINUE,
                    'message' => 'the password must be identical.'
                ]
            );
        }
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserByConfirmationToken($token);
        if (null === $user) {
            return new JsonResponse(
                [
                    'error' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => 'The user with confirmation token does not exist for value =' . $token
                ]
            );
        }
        if ($request->getMethod() == "POST") {
            $user->setPlainPassword($secondPassword);
            $user->setConfirmationToken(null);
            $userManager->updateUser($user, true);
            return new JsonResponse(
                [
                    "code" => Response::HTTP_OK,
                    "message" => "Password resetting"
                ]
            );
        }
    }

    /**
     * @param $email
     * @param $token
     */
    public function sendMailReset($email, $token){
        $url = $this->container->getParameter("host_preprod")."/#/wd/reset/".$token;
        $modelEMail = $this->get(EmailAutomatiqueManager::SERVICE_NAME)->findBy(
            ['declenchement' => Constant::RESET_PASSWORD, 'deletedAt' => null],
            ['id' => 'DESC'], 1
        );
        $dataFrom['send_by'] = $modelEMail[0]->getEmitter();
        $template = $modelEMail[0]->getTemplate();
        $modele = ["__url__"];
        $real = [$url];
        $template = str_replace($modele, $real, $template);
        $this->container->get('app.mailer')->sendMailGrid("Resset password", $email, $template, $dataFrom);
    }
}
