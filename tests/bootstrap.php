<?php

require_once __DIR__.'/../vendor/autoload.php';

use Harrisonratcliffe\LaravelApiResponses\Tests\UsesApiResponses;
use Orchestra\Testbench\TestCase;

// This class is only for PHPStan to correctly infer types.
abstract class PhpStanTestBase extends TestCase
{
    use UsesApiResponses;
}
