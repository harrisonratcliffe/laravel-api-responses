<?php

use Harrisonratcliffe\LaravelApiResponses\Tests\UsesApiResponses;
use Orchestra\Testbench\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test case.
| For example, to set up your application before each test, you might provide a SetUp method.
| It's a great place to tweak the Laravel application or other binaries.
|
*/

uses(TestCase::class, UsesApiResponses::class)->in(__DIR__);
