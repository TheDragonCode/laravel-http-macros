<?php

namespace Tests;

use DragonCode\LaravelHttpMacros\Macros\Responses\ToDataCollectionMacro;
use DragonCode\LaravelHttpMacros\Macros\Responses\ToDataMacro;
use DragonCode\LaravelHttpMacros\ServiceProvider;
use Illuminate\Config\Repository;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Spatie\LaravelData\LaravelDataServiceProvider;
use Tests\Fixtures\Logging\MessageFormater;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            LaravelDataServiceProvider::class,
            ServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        tap($app['config'], function (Repository $config) {
            $config->set('http.macros.response', [
                ToDataMacro::class,
                ToDataCollectionMacro::class,

                'toFoo' => ToDataMacro::class,
            ]);

            $config->set('logging.channels.foo', [
                'driver' => 'single',
                'path'   => storage_path('logs/foo.log'),
            ]);

            $config->set('logging.channels.bar', [
                'driver'    => 'single',
                'path'      => storage_path('logs/bar.log'),
                'formatter' => MessageFormater::class,
            ]);
        });
    }
}
