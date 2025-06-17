<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Orchestra\Testbench\TestCase;
use Harrisonratcliffe\LaravelApiResponses\Tests\UsesApiResponses;

// This class is only for PHPStan to correctly infer types.
abstract class PhpStanTestBase extends TestCase
{
    use UsesApiResponses;
} 