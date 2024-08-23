<?php

declare(strict_types=1);

namespace DragonCode\LaravelHttpMacros;

use DragonCode\LaravelHttpMacros\Commands\GenerateHelperCommand;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

use function app;
use function config;
use function is_string;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->registerConfig();
    }

    public function boot(): void
    {
        $this->publishConfig();
        $this->bootMacros();
        $this->bootCommands();
    }

    protected function bootCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateHelperCommand::class,
            ]);
        }
    }

    protected function bootMacros(): void
    {
        foreach ($this->macros() as $name => $macro) {
            Response::macro(
                name : is_string($name) ? $name : $macro::name(),
                macro: $macro::callback()
            );
        }
    }

    protected function macros(): array
    {
        return config('http.macros.response', []);
    }

    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            path: __DIR__ . '/../config/http.php',
            key : 'http'
        );
    }

    protected function publishConfig(): void
    {
        $this->publishes([
            __DIR__ . '/../config/http.php' => app()->configPath('http.php'),
        ]);
    }
}
