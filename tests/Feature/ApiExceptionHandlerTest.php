<?php

use Harrisonratcliffe\LaravelApiResponses\ApiExceptionHandler;
use Harrisonratcliffe\LaravelApiResponses\Services\ApiResponseService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\Support\CustomException;

describe('ApiExceptionHandler', function () {
    it('renders a not found http exception', function () {
        $service = new ApiResponseService;
        $handler = new ApiExceptionHandler($service);
        $exception = new NotFoundHttpException('Test Not Found');
        $response = $handler->renderApiException($exception);
        expect($response->getStatusCode())->toBe(404);
        expect($response->getData()->message)->toBe(config('api-responses.http_not_found'));
    });

    it('renders a model not found exception', function () {
        $service = new ApiResponseService;
        $handler = new ApiExceptionHandler($service);
        $exception = new ModelNotFoundException('Model not found');
        $response = $handler->renderApiException($exception);
        expect($response->getStatusCode())->toBe(404);
        expect($response->getData()->message)->toBe(config('api-responses.model_not_found'));
    });

    it('renders a not authorized exception', function () {
        $service = new ApiResponseService;
        $handler = new ApiExceptionHandler($service);
        $exception = new AuthorizationException('Not authorized');
        $response = $handler->renderApiException($exception);
        expect($response->getStatusCode())->toBe(403);
        expect($response->getData()->message)->toBe(config('api-responses.not_authorized'));
    });

    it('renders an access denied http exception', function () {
        $service = new ApiResponseService;
        $handler = new ApiExceptionHandler($service);
        $exception = new AccessDeniedHttpException('Access denied');
        $response = $handler->renderApiException($exception);
        expect($response->getStatusCode())->toBe(403);
        expect($response->getData()->message)->toBe(config('api-responses.not_authorized'));
    });

    it('renders an authentication exception', function () {
        $service = new ApiResponseService;
        $handler = new ApiExceptionHandler($service);
        $exception = new AuthenticationException;
        $response = $handler->renderApiException($exception);
        expect($response->getStatusCode())->toBe(401);
        expect($response->getData()->message)->toBe(config('api-responses.unauthenticated'));
    });

    it('renders a validation exception', function () {
        $service = new ApiResponseService;
        $handler = new ApiExceptionHandler($service);
        $exception = ValidationException::withMessages(['field' => ['Invalid']]);
        $response = $handler->renderApiException($exception);
        expect($response->getStatusCode())->toBe(422);
        expect($response->getData()->message)->toBe(config('api-responses.validation'));
        expect((array) $response->getData()->details)->toHaveKey('field');
    });

    it('renders a rate limit exception', function () {
        $service = new ApiResponseService;
        $handler = new ApiExceptionHandler($service);
        $exception = new ThrottleRequestsException('Rate limit');
        $response = $handler->renderApiException($exception);
        expect($response->getStatusCode())->toBe(429);
        expect($response->getData()->message)->toBe(config('api-responses.rate_limit'));
    });

    it('renders an unknown error (500) with show_500_error_message false', function () {
        $service = new ApiResponseService;
        $handler = new ApiExceptionHandler($service);
        config(['api-responses.show_500_error_message' => false]);
        $exception = new \Exception('Internal error');
        $response = $handler->renderApiException($exception);
        expect($response->getStatusCode())->toBe(500);
        expect($response->getData()->message)->toBe(config('api-responses.unknown_error'));
    });

    it('renders an unknown error (500) with show_500_error_message true', function () {
        $service = new ApiResponseService;
        $handler = new ApiExceptionHandler($service);
        config(['api-responses.show_500_error_message' => true]);
        $exception = new \Exception('Internal error');
        $response = $handler->renderApiException($exception);
        expect($response->getStatusCode())->toBe(500);
        expect($response->getData()->message)->toBe('Internal error');
    });

    it('includes debug data when debug_mode is enabled', function () {
        $service = new ApiResponseService;
        $handler = new ApiExceptionHandler($service);
        config(['api-responses.debug_mode' => true]);
        $exception = new \Exception('Debug error');
        $response = $handler->renderApiException($exception);
        expect($response->getData()->debug)->not->toBeNull();
    });

    it('does not include debug data when debug_mode is disabled', function () {
        $service = new ApiResponseService;
        $handler = new ApiExceptionHandler($service);
        config(['api-responses.debug_mode' => false]);
        $exception = new \Exception('Debug error');
        $response = $handler->renderApiException($exception);
        expect(isset($response->getData()->debug))->toBeFalse();
    });

    it('renders a custom exception mapped in config', function () {
        $service = new ApiResponseService;
        $handler = new ApiExceptionHandler($service);
        config(['api-responses.custom_exceptions' => [
            \Tests\Support\CustomException::class => [
                'message' => 'Custom mapped error',
                'status' => 418,
            ],
        ]]);
        $exception = new \Tests\Support\CustomException('Should not show');
        $response = $handler->renderApiException($exception);
        expect($response->getStatusCode())->toBe(418);
        expect($response->getData()->message)->toBe('Custom mapped error');
    });
});
