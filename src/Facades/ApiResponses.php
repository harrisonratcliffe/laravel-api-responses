<?php

namespace Harrisonratcliffe\LaravelApiResponses\Facades;

use Illuminate\Support\Facades\Facade;

class ApiResponses extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'api-response';
    }
}
