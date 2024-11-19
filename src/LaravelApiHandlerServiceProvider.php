<?php

namespace Harrisonratcliffe\LaravelApiHandler;

use Harrisonratcliffe\LaravelApiHandler\Services\ApiResponseService;
use Illuminate\Support\ServiceProvider;

class LaravelApiHandlerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-api-handler.php', 'laravel-api-handler');

        $this->app->singleton('api-response', function () {
            return new ApiResponseService;
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/laravel-api-handler.php' => config_path('laravel-api-handler.php'),
        ], 'laravelapihandler-config');
    }
}
