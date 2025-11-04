<?php

namespace App\ProcessFeature\Infrastructure\EventListener\Exception;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ApiExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $request = $event->getRequest();

        $expectsJson = str_contains($request->headers->get('Accept', ''), 'application/json')
            || $request->getContentTypeFormat() === 'json';

        if (!$expectsJson) {
            return;
        }

        $exception = $event->getThrowable();
        $statusCode = $exception instanceof HttpExceptionInterface
            ? $exception->getStatusCode()
            : JsonResponse::HTTP_INTERNAL_SERVER_ERROR;

        $data = [
            'error' => [
                'message' => $exception->getMessage(),
                'code' => $statusCode,
            ],
        ];

        // Uncomment if you wish to have entire trace.
        // if ($_ENV['APP_ENV'] === 'dev') {
        //     $data['error']['trace'] = $exception->getTraceAsString();
        // }

        $event->setResponse(new JsonResponse($data, $statusCode));
    }
}
