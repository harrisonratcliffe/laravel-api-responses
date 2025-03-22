<?php

namespace Harrisonratcliffe\LaravelApiResponses\Tests;

use Harrisonratcliffe\LaravelApiResponses\ApiResponsesServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Harrisonratcliffe\\ApiResponses\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            ApiResponsesServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        // $migration = include __DIR__.'/../database/migrations/MIGRATION.php';
        // $migration->up();
    }
}
