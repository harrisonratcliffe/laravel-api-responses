<?php

declare(strict_types=1);

namespace Harrisonratcliffe\LaravelApiResponses;

use Harrisonratcliffe\LaravelApiResponses\Services\ApiResponseService;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

/**
 * @internal
 */
class ApiResponsesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('api-responses')
            ->hasConfigFile('api-responses');
    }

    public function registeringPackage(): void
    {
        $this->app->singleton('api-response', function () {
            return new ApiResponseService;
        });
    }
}
