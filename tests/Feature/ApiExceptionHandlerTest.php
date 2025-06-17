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
use Throwable;

describe('ApiExceptionHandler', function () {
    beforeEach(function () {
        $this->service = new ApiResponseService;
        $this->handler = new ApiExceptionHandler($this->service);
    });

    it('renders a not found http exception', function () {
        $exception = new NotFoundHttpException('Test Not Found');
        $response = $this->handler->renderApiException($exception);
        expect($response->getStatusCode())->toBe(404);
        expect($response->getData()->message)->toBe(config('api-responses.http_not_found'));
    });

    it('renders a model not found exception', function () {
        $exception = new ModelNotFoundException('Model not found');
        $response = $this->handler->renderApiException($exception);
        expect($response->getStatusCode())->toBe(404);
        expect($response->getData()->message)->toBe(config('api-responses.model_not_found'));
    });

    it('renders a not authorized exception', function () {
        $exception = new AuthorizationException('Not authorized');
        $response = $this->handler->renderApiException($exception);
        expect($response->getStatusCode())->toBe(403);
        expect($response->getData()->message)->toBe(config('api-responses.not_authorized'));
    });

    it('renders an access denied http exception', function () {
        $exception = new AccessDeniedHttpException('Access denied');
        $response = $this->handler->renderApiException($exception);
        expect($response->getStatusCode())->toBe(403);
        expect($response->getData()->message)->toBe(config('api-responses.not_authorized'));
    });

    it('renders an authentication exception', function () {
        $exception = new AuthenticationException;
        $response = $this->handler->renderApiException($exception);
        expect($response->getStatusCode())->toBe(401);
        expect($response->getData()->message)->toBe(config('api-responses.unauthenticated'));
    });

    it('renders a validation exception', function () {
        $exception = ValidationException::withMessages(['field' => ['Invalid']]);
        $response = $this->handler->renderApiException($exception);
        expect($response->getStatusCode())->toBe(422);
        expect($response->getData()->message)->toBe(config('api-responses.validation'));
        expect((array) $response->getData()->details)->toHaveKey('field');
    });

    it('renders a rate limit exception', function () {
        $exception = new ThrottleRequestsException('Rate limit');
        $response = $this->handler->renderApiException($exception);
        expect($response->getStatusCode())->toBe(429);
        expect($response->getData()->message)->toBe(config('api-responses.rate_limit'));
    });

    it('renders an unknown error (500) with show_500_error_message false', function () {
        config(['api-responses.show_500_error_message' => false]);
        $exception = new \Exception('Internal error');
        $response = $this->handler->renderApiException($exception);
        expect($response->getStatusCode())->toBe(500);
        expect($response->getData()->message)->toBe(config('api-responses.unknown_error'));
    });

    it('renders an unknown error (500) with show_500_error_message true', function () {
        config(['api-responses.show_500_error_message' => true]);
        $exception = new \Exception('Internal error');
        $response = $this->handler->renderApiException($exception);
        expect($response->getStatusCode())->toBe(500);
        expect($response->getData()->message)->toBe('Internal error');
    });

    it('includes debug data when debug_mode is enabled', function () {
        config(['api-responses.debug_mode' => true]);
        $exception = new \Exception('Debug error');
        $response = $this->handler->renderApiException($exception);
        expect($response->getData()->debug)->not->toBeNull();
    });

    it('does not include debug data when debug_mode is disabled', function () {
        config(['api-responses.debug_mode' => false]);
        $exception = new \Exception('Debug error');
        $response = $this->handler->renderApiException($exception);
        expect(isset($response->getData()->debug))->toBeFalse();
    });

    it('renders a custom exception mapped in config', function () {
        // Define a custom exception class inline
        if (!class_exists('App\\Exceptions\\CustomException')) {
            eval('namespace App\\Exceptions; class CustomException extends \\Exception {}');
        }
        config(['api-responses.custom_exceptions' => [
            'App\\Exceptions\\CustomException' => [
                'message' => 'Custom mapped error',
                'status' => 418,
            ],
        ]]);
        $exception = new App\Exceptions\CustomException('Should not show');
        $handler = new Harrisonratcliffe\LaravelApiResponses\ApiExceptionHandler(new ApiResponseService);
        $response = $handler->renderApiException($exception);
        expect($response->getStatusCode())->toBe(418);
        expect($response->getData()->message)->toBe('Custom mapped error');
    });
});
