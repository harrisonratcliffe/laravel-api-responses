<?php

use Harrisonratcliffe\LaravelApiResponses\ApiResponsesServiceProvider;
use Harrisonratcliffe\LaravelApiResponses\Services\ApiResponseService;
use Illuminate\Support\Facades\App;

it('registers the api-response singleton', function () {
    app()->register(ApiResponsesServiceProvider::class);

    \PHPUnit\Framework\Assert::assertInstanceOf(ApiResponseService::class, App::make('api-response'));
});
