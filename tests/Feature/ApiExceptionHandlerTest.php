<?php

use Harrisonratcliffe\LaravelApiHandler\ApiExceptionHandler;
use Harrisonratcliffe\LaravelApiHandler\Services\ApiResponseService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

it('renders a not found http exception', function () {
    $handler = new ApiExceptionHandler(new ApiResponseService());
    $exception = new NotFoundHttpException('Test Not Found');

    /** @var JsonResponse $response */
    $response = $handler->renderApiException($exception);

    expect($response->getStatusCode())->toBe(404);
    expect($response->getData()->error->message)->toBe('Test Not Found');
});

it('renders an authentication exception', function () {
    $handler = new ApiExceptionHandler(new ApiResponseService());
    $exception = new AuthenticationException();

    /** @var JsonResponse $response */
    $response = $handler->renderApiException($exception);

    expect($response->getStatusCode())->toBe(401);
    expect($response->getData()->error->message)->toBe(config('laravel-api-handler.unauthenticated'));
});
