<?php

namespace App\EventListener;


use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTExpiredEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

class JwtExpiredListener
{
    /**
    * @param JWTExpiredEvent $event
    */
    public function onJWTExpired(JWTExpiredEvent $event)
    {
        $data = [
            'status' => 'error',
            'code' => 401,
            'message' => 'Your token is expired, please renew it.'
        ];

        $response = new JsonResponse($data,401);

        $event->setResponse($response);

    }
}