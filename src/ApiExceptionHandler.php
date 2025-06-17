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
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ApiExceptionHandler extends Exception
{
    protected ApiResponseService $apiResponseService;

    public function __construct(ApiResponseService $apiResponseService)
    {
        parent::__construct();
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
            isset($responseData['details']) ? $responseData['details'] : null,
            null,
            $debugData
        );
    }

    /**
     * Prepare the response data for a given exception.
     *
     * @param  Throwable  $exception  The exception to prepare data for.
     * @return array<mixed> An array containing the status code and message.
     */
    private function prepareApiExceptionData(Throwable $exception): array
    {
        $responseData = [];
        $message = $exception->getMessage();

        if ($exception instanceof NotFoundHttpException) {
            $responseData['message'] = config('api-responses.always_use_default_responses') ? config('api-responses.http_not_found') : $message;
            $responseData['statusCode'] = 404;
        } elseif ($exception instanceof MethodNotAllowedHttpException) {
            $responseData['message'] = config('api-responses.always_use_default_responses') ? config('api-responses.method_not_allowed') : $message;
            $responseData['statusCode'] = 405;
        } elseif ($exception instanceof ModelNotFoundException) {
            $responseData['message'] = config('api-responses.always_use_default_responses') ? config('api-responses.http_not_found') : $message;
            $responseData['statusCode'] = 404;
        } elseif ($exception instanceof AuthorizationException || $exception instanceof AccessDeniedHttpException) {
            $responseData['message'] = config('api-responses.always_use_default_responses') ? config('api-responses.not_authorized') : $message;
            $responseData['statusCode'] = 403;
        } elseif ($exception instanceof AuthenticationException) {
            $responseData['message'] = config('api-responses.always_use_default_responses') ? config('api-responses.unauthenticated') : $message;
            $responseData['statusCode'] = 401;
        } elseif ($exception instanceof ValidationException) {
            $responseData['message'] = config('api-responses.always_use_default_responses') ? config('api-responses.validation') : $message;
            $responseData['statusCode'] = 422;
            $responseData['details'] = $exception->errors();
        } elseif ($exception instanceof ThrottleRequestsException) {
            $responseData['message'] = config('api-responses.always_use_default_responses') ? config('api-responses.rate_limit') : $message;
            $responseData['statusCode'] = 429;
        } else {
            if (config('api-responses.show_500_error_message') && !empty($message)) {
                $responseData['message'] = $message;
            } else {
                $responseData['message'] = config('api-responses.unknown_error');
            }

            $responseData['statusCode'] = 500;

            if (config('api-responses.debug_mode')) {
                $responseData['debug'] = $this->extractExceptionData($exception);
            }
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

    /**
     * Prepare a user-friendly error message from the exception.
     *
     * @param  Throwable  $exception  The exception to extract the message from.
     * @return string A user-friendly error message.
     */
    private function prepareExceptionMessage(Throwable $exception): string
    {
        return $exception->getMessage() ?: config('api-responses.unknown_error');
    }
}
