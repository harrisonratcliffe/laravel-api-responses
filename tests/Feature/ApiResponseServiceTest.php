<?php

use Harrisonratcliffe\LaravelApiHandler\Services\ApiResponseService;
use Illuminate\Http\JsonResponse;

it('returns a success response', function () {
    $service = new ApiResponseService;
    /** @var JsonResponse $response */
    $response = $service->successResponse('Operation successful', ['key' => 'value']);

    expect($response->getStatusCode())->toBe(200);
    expect($response->getData()->status)->toBe('success');
    expect($response->getData()->message)->toBe('Operation successful');
    expect((array) $response->getData()->data)->toEqual(['key' => 'value']); // Cast to array
});

it('returns an error response', function () {
    $service = new ApiResponseService;
    /** @var JsonResponse $response */
    $response = $service->errorResponse('An error occurred', 400);

    expect($response->getStatusCode())->toBe(400);
    expect($response->getData()->status)->toBe('error');
    expect($response->getData()->error->message)->toBe('An error occurred');
});
