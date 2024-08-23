<?php

declare(strict_types=1);

namespace DragonCode\LaravelHttpMacros;

use DragonCode\LaravelHttpMacros\Commands\GenerateHelperCommand;
use DragonCode\LaravelHttpMacros\Macros\Macro;
use Illuminate\Http\Client\Request;
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
        $this->bootCommands();

        $this->bootRequestMacros();
        $this->bootResponseMacros();
    }

    protected function bootCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateHelperCommand::class,
            ]);
        }
    }

    protected function bootRequestMacros(): void
    {
        foreach ($this->requestMacros() as $name => $macro) {
            Request::macro($this->resolveName($name, $macro), $macro::callback());
        }
    }

    protected function bootResponseMacros(): void
    {
        foreach ($this->responseMacros() as $name => $macro) {
            Response::macro($this->resolveName($name, $macro), $macro::callback());
        }
    }

    protected function resolveName(int|string $name, Macro|string $macro): string
    {
        return is_string($name) ? $name : $macro::name();
    }

    /**
     * @return array<class-string|Macro>
     */
    protected function requestMacros(): array
    {
        return config('http.macros.request', []);
    }

    /**
     * @return array<class-string|Macro>
     */
    protected function responseMacros(): array
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
