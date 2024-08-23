<?php

declare(strict_types=1);

namespace DragonCode\LaravelHttpMacros;

use DragonCode\LaravelHttpMacros\Macros\Macro;
use DragonCode\LaravelHttpMacros\Macros\ToDataCollectionMacro;
use DragonCode\LaravelHttpMacros\Macros\ToDataMacro;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /** @var array<string|Macro> */
    protected array $macros = [
        ToDataMacro::class,
        ToDataCollectionMacro::class,
    ];

    public function boot(): void
    {
        foreach ($this->macros as $macros) {
            $this->bootMacros($macros);
        }
    }

    protected function bootMacros(Macro|string $macros): void
    {
        Response::macro($macros::name(), $macros::callback());
    }
}
