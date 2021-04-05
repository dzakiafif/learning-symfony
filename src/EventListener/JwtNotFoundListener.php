<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

class JwtNotFoundListener
{
    /**
    * @param JWTNotFoundEvent $event
    */
    public function onJWTNotFound(JWTNotFoundEvent $event)
    {
        $data = [
            'status'  => 'error',
            'code' => 403,
            'message' => 'Missing token. please check it again',
        ];

        $response = new JsonResponse($data, 403);

        $event->setResponse($response);
    }
}