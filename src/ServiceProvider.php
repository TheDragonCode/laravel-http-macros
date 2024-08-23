<?php

declare(strict_types=1);

namespace DragonCode\LaravelHttpMacros;

use Illuminate\Http\Client\Response;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

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
            __DIR__ . '/../config/http.php' => config_path('http.php'),
        ]);
    }
}
