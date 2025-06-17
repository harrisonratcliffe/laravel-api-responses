<?php

namespace Harrisonratcliffe\LaravelApiResponses\Tests;

use Harrisonratcliffe\LaravelApiResponses\ApiResponsesServiceProvider;
use Orchestra\Testbench\TestCase;

trait UsesApiResponses
{
    protected function getPackageProviders($app)
    {
        return [
            ApiResponsesServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
    }
} 