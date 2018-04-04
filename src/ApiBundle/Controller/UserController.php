<?php

namespace ApiBundle\Controller;

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

class UserController extends FOSRestController
{
    /**
     * Send mail to reset password user
     *
     * @Method("GET")
     * @Route("/reset/password/send-mail")
     * @return                             \Symfony\Component\HttpFoundation\Response
     */
    public function resetPasswordRequestAction(Request $request)
    {
        $email = $request->query->get('email');
        $user = $this->get('fos_user.user_manager')->findUserByEmail($email);
        if (null === $user) {
            return new JsonResponse(['error' => 'User not found']);
        }
        if (null === $user->getConfirmationToken()) {
            $tokenGenerator = $this->get('fos_user.util.token_generator');
            $user->setConfirmationToken($tokenGenerator->generateToken());
        }

        $this->get('fos_user.mailer')->sendResettingEmailMessage($user);
        $user->setPasswordRequestedAt(new \DateTime());
        $this->get('fos_user.user_manager')->updateUser($user);
        return new JsonResponse(["code" => Response::HTTP_OK, "token" => $user->getConfirmationToken()]);
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
}
