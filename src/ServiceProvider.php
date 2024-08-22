<?php

declare(strict_types=1);

namespace DragonCode\LaravelHttpMacros;

use Closure;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot(): void
    {
        $this->toData();
        $this->toDataCollection();
    }

    /**
     * Get the JSON decoded body of the response as a class instance.
     *
     * @return void
     */
    protected function toData(): void
    {
        Response::macro('toData', function (Closure|string $class, int|string|null $key = null): mixed {
            if (is_callable($class)) {
                return $class($this->json($key));
            }

            if (is_null($data = $this->json($key))) {
                return null;
            }

            if (method_exists($class, 'from')) {
                return $class::from($data);
            }

            return new $class(...$data);
        });
    }

    /**
     * Get the JSON decoded body of the response as a collection.
     *
     * @return void
     */
    protected function toDataCollection(): void
    {
        Response::macro('toDataCollection', function (Closure|string $class, int|string|null $key = null): Collection {
            if (is_null($data = $this->json($key))) {
                return collect();
            }

            if (is_callable($class)) {
                $result = $class($data);

                return $result instanceof Collection ? $result : collect([$result]);
            }

            if (method_exists($class, 'collect')) {
                $result = $class::collect($data);

                if ($result instanceof Collection) {
                    return $result;
                }

                return is_array($result) ? collect($result) : collect([$result]);
            }

            return collect($data)->map(function (array $item) use ($class) {
                if (method_exists($class, 'from')) {
                    return $class::from($item);
                }

                return new $class(...$item);
            });
        });
    }
}
