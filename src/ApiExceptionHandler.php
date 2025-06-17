<?php

namespace Harrisonratcliffe\LaravelApiResponses;

use Exception;
use Harrisonratcliffe\LaravelApiResponses\Services\ApiResponseService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ApiExceptionHandler
{
    protected ApiResponseService $apiResponseService;

    public function __construct(ApiResponseService $apiResponseService)
    {
        $this->apiResponseService = $apiResponseService;
    }

    /**
     * Render an API exception response.
     *
     * @param  Throwable  $exception  The exception to be rendered.
     * @return JsonResponse A JSON response representing the error.
     */
    public function renderApiException(Throwable $exception): JsonResponse
    {
        $responseData = $this->prepareApiExceptionData($exception);

        $debugData = config('api-responses.debug_mode') ? $this->extractExceptionData($exception) : null;

        return $this->apiResponseService->error(
            $responseData['message'],
            $responseData['statusCode'],
            $responseData['details'] ?? null,
            null,
            $debugData
        );
    }

    /**
     * Prepare the response data for a given exception.
     *
     * @param  Throwable  $exception  The exception to prepare data for.
     * @return array<mixed> An array containing the status code, message, and optional details.
     */
    private function prepareApiExceptionData(Throwable $exception): array
    {
        $message = $exception->getMessage();
        $statusCode = 500;
        $details = null;

        switch (true) {
            case $exception instanceof NotFoundHttpException:
            case $exception instanceof ModelNotFoundException:
                $message = config('api-responses.http_not_found');
                $statusCode = 404;
                break;
            case $exception instanceof MethodNotAllowedHttpException:
                $statusCode = 405;
                break;
            case $exception instanceof AuthorizationException:
            case $exception instanceof AccessDeniedHttpException:
                $message = config('api-responses.not_authorized');
                $statusCode = 403;
                break;
            case $exception instanceof AuthenticationException:
                $message = config('api-responses.unauthenticated');
                $statusCode = 401;
                break;
            case $exception instanceof ValidationException:
                $message = config('api-responses.validation');
                $statusCode = 422;
                $details = $exception->errors();
                break;
            case $exception instanceof ThrottleRequestsException:
                $message = config('api-responses.rate_limit');
                $statusCode = 429;
                break;
            case $exception instanceof HttpResponseException:
                // This exception typically means a response has already been prepared.
                // If this handler is explicitly called for it, we'll let it fall through to default
                // or handle as a generic HTTP exception if it implements HttpExceptionInterface.
                $statusCode = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : 500;
                break;
            default:
                if (config('api-responses.show_500_error_message') && ! empty($message)) {
                    // Use the exception's message if configured and not empty
                } else {
                    $message = config('api-responses.unknown_error');
                }
                $statusCode = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : 500;
                break;
        }

        // Ensure message is set if it was empty and not specifically handled above
        if (empty($message)) {
            $message = $exception->getMessage() ?: config('api-responses.unknown_error');
        }

        $responseData = [
            'message' => $message,
            'statusCode' => $statusCode,
        ];

        if ($details) {
            $responseData['details'] = $details;
        }

        return $responseData;
    }

    /**
     * Extract detailed exception data if in debug mode.
     *
     * @param  Throwable  $exception  The exception to extract data from.
     * @return array<mixed> An array containing detailed exception information.
     */
    private function extractExceptionData(Throwable $exception): array
    {
        return [
            'message' => $exception->getMessage(),
            'exception' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => collect($exception->getTrace())->map(function ($trace) {
                return Arr::except($trace, ['args']);
            })->all(),
        ];
    }
}
