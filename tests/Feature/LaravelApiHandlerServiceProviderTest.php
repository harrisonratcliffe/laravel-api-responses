<?php

use Harrisonratcliffe\LaravelApiHandler\LaravelApiHandlerServiceProvider;
use Harrisonratcliffe\LaravelApiHandler\Services\ApiResponseService;
use Illuminate\Support\Facades\App;

it('registers the api-response singleton', function () {
    $this->app->register(LaravelApiHandlerServiceProvider::class);

    $this->assertInstanceOf(ApiResponseService::class, App::make('api-response'));
});
