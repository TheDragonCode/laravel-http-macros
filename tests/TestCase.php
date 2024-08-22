<?php

namespace Tests;

use DragonCode\LaravelHttpMacros\ServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Spatie\LaravelData\LaravelDataServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            LaravelDataServiceProvider::class,
            ServiceProvider::class,
        ];
    }
}
