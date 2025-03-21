<?php

use Harrisonratcliffe\LaravelApiResponses\LaravelApiResponsesServiceProvider;
use Harrisonratcliffe\LaravelApiResponses\Services\ApiResponseService;
use Illuminate\Support\Facades\App;

it('registers the api-response singleton', function () {
    $this->app->register(LaravelApiResponsesServiceProvider::class);

    $this->assertInstanceOf(ApiResponseService::class, App::make('api-response'));
});
