<?php

declare(strict_types=1);

namespace App\Authorization\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class SuccessHandler extends AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    protected $jwtManager;
    protected $dispatcher;

    /**
     * @param JWTManager               $jwtManager
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(JWTManager $jwtManager, EventDispatcherInterface $dispatcher)
    {
        $this->jwtManager = $jwtManager;
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        return $this->handleAuthenticationSuccess($token->getUser());
    }

    public function handleAuthenticationSuccess(UserInterface $user, $jwt = null)
    {
        if (null === $jwt) {
            $jwt = $this->jwtManager->create($user);
        }

        $response = new JWTAuthenticationSuccessResponse($jwt, ['user' => $user->getId()]);
        $event    = new AuthenticationSuccessEvent(['token' => $jwt, 'user' => $user->getId()], $user, $response);

        $this->dispatcher->dispatch($event, Events::AUTHENTICATION_SUCCESS);
        $response->setData($event->getData());

        return $response;
    }
}
