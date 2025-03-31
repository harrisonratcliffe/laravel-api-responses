<?php

namespace Harrisonratcliffe\LaravelApiResponses;

use Exception;
use Harrisonratcliffe\LaravelApiResponses\Services\ApiResponseService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
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
            $responseData['message'] = !empty($message) ? $message : config('api-responses.http_not_found');
            $responseData['statusCode'] = 404;
        } elseif ($exception instanceof MethodNotAllowedHttpException) {
            $responseData['message'] = !empty($message) ? $message : config('api-responses.method_not_allowed');
            $responseData['statusCode'] = 405;
        } elseif ($exception instanceof ModelNotFoundException) {
            $responseData['message'] = !empty($message)
                ? $message
                : sprintf(config('api-responses.model_not_found'), $this->modelNotFoundMessage($exception));
            $responseData['statusCode'] = 404;
        } elseif ($exception instanceof AuthenticationException) {
            $responseData['message'] = !empty($message) ? $message : config('api-responses.unauthenticated');
            $responseData['statusCode'] = 401;
        } elseif ($exception instanceof ValidationException) {
            $responseData['message'] = $message ?: config('api-responses.validation_error');
            $responseData['statusCode'] = 422;
            $responseData['details'] = $exception->errors();
        } else {
            $responseData['message'] = !empty($message)
                ? $message
                : (config('api-responses.show_500_error_message')
                    ? $this->prepareExceptionMessage($exception)
                    : config('api-responses.unknown_error'));
            $responseData['statusCode'] = ($exception instanceof HttpExceptionInterface) ? $exception->getStatusCode() : 500;
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

    /**
     * Generate a model not found message based on the exception.
     *
     * @param  ModelNotFoundException  $exception  The model not found exception.
     * @return string A message indicating which resource was not found.
     */
    private function modelNotFoundMessage(ModelNotFoundException $exception): string
    {
        $modelClass = $exception->getModel();

        if ($modelClass !== null) {
            return Str::lower(ltrim(preg_replace('/[A-Z]/', ' $0', class_basename($modelClass))));
        }

        return 'resource';
    }
}
