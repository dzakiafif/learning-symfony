<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthenticationFailureListener
{
    /**
    * @param AuthenticationFailureEvent $event
    */
    public function onAuthenticationFailureResponse(AuthenticationFailureEvent $event)
    {
        $data = [
            'status'  => 'error',
            'code' => 401,
            'message' => 'Bad credentials, please verify that your username/password are correctly set',
        ];

        $response = new JsonResponse($data,401);

        $event->setResponse($response);
    }
    
}