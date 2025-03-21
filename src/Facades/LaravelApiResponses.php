<?php

namespace Harrisonratcliffe\LaravelApiResponses\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelApiResponses extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'api-responses';
    }
}
