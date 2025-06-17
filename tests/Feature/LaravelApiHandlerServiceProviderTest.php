<?php

use Harrisonratcliffe\LaravelApiResponses\ApiResponsesServiceProvider;
use Harrisonratcliffe\LaravelApiResponses\Services\ApiResponseService;
use Illuminate\Support\Facades\App;

it('registers the api-response singleton', function () {
    $this->app->register(ApiResponsesServiceProvider::class);

    $this->assertInstanceOf(ApiResponseService::class, App::make('api-response'));
});
