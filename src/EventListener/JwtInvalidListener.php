<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTInvalidEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

class JwtInvalidListener
{
    /**
     * @param JWTInvalidEvent $event
     */
    public function onJWTInvalid(JWTInvalidEvent $event)
    {
        $data = [
            'status' => 'error',
            'code' => 403,
            'message' => 'Your token is invalid, please login again to get a new one'
        ];

        $response = new JsonResponse($data,403);
        $event->setResponse($response);
    }
}