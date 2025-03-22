<?php

namespace Harrisonratcliffe\LaravelApiResponses;

use Harrisonratcliffe\LaravelApiResponses\Services\ApiResponseService;
use Illuminate\Support\ServiceProvider;

class ApiResponsesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/api-responses.php', 'api-responses');

        $this->app->singleton('api-response', function () {
            return new ApiResponseService;
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/api-responses.php' => config_path('api-responses.php'),
        ], 'apiresponses-config');
    }
}
