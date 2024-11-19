<?php

namespace Harrisonratcliffe\LaravelApiHandler\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelApiHandler extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'api-response';
    }
}
