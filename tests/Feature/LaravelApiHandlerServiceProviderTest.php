<?php

use Harrisonratcliffe\LaravelApiResponses\ApiResponsesServiceProvider;
use Harrisonratcliffe\LaravelApiResponses\Services\ApiResponseService;
use Illuminate\Support\Facades\App;

it('registers the api-response singleton', function () {
    $app = app();
    $app->register(ApiResponsesServiceProvider::class);
    $apiResponseService = $app->make(ApiResponseService::class);
    expect($apiResponseService)->toBeInstanceOf(ApiResponseService::class);
});

it('merges the config file', function () {
    $app = app();
    $app->register(ApiResponsesServiceProvider::class);
    expect(config('api-responses.success_response'))->toBe('API request processed successfully.');
});
