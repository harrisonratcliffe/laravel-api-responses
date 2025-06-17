<?php

use Harrisonratcliffe\LaravelApiResponses\ApiExceptionHandler;
use Harrisonratcliffe\LaravelApiResponses\Services\ApiResponseService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

it('renders a not found http exception', function () {
    $handler = new ApiExceptionHandler(new ApiResponseService);
    $exception = new NotFoundHttpException('Test Not Found');

    /** @var JsonResponse $response */
    $response = $handler->renderApiException($exception);

    expect($response->getStatusCode())->toBe(404);
    expect($response->getData()->message)->toBe(config('api-responses.http_not_found'));
});

it('renders an authentication exception', function () {
    $handler = new ApiExceptionHandler(new ApiResponseService);
    $exception = new AuthenticationException;

    /** @var JsonResponse $response */
    $response = $handler->renderApiException($exception);

    expect($response->getStatusCode())->toBe(401);
    expect($response->getData()->message)->toBe(config('api-responses.unauthenticated'));
});
