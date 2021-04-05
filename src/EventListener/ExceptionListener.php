<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $code = $exception->getCode();

        if($exception instanceof HttpExceptionInterface) {
            $code = $exception->getStatusCode();
        }

        $recentCode = $code == 0 ? 400 : $code;

        $message = [
            'status' => 'error',
            'code' => $recentCode,
            'message' => $exception->getMessage()
        ];

        $response = new JsonResponse($message,$recentCode);

        $event->setResponse($response);
    }
}