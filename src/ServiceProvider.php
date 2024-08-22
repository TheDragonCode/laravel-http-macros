<?php

declare(strict_types=1);

namespace DragonCode\LaravelHttpMacros;

use DragonCode\LaravelHttpMacros\Macroses\Macros;
use DragonCode\LaravelHttpMacros\Macroses\ToDataCollectionMacros;
use DragonCode\LaravelHttpMacros\Macroses\ToDataMacros;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /** @var array<string|Macros> */
    protected array $macroses = [
        ToDataMacros::class,
        ToDataCollectionMacros::class,
    ];

    public function boot(): void
    {
        foreach ($this->macroses as $macros) {
            $this->bootMacros($macros);
        }
    }

    protected function bootMacros(Macros|string $macros): void
    {
        Response::macro($macros::name(), $macros::callback());
    }
}
