<?php

declare(strict_types=1);

namespace DragonCode\LaravelHttpMacros\Macros;

use Closure;
use Illuminate\Support\Collection;

use function collect;
use function is_array;
use function is_callable;
use function is_null;
use function method_exists;

/**
 * Get the JSON decoded body of the response as a collection.
 */
class ToDataCollectionMacro extends Macro
{
    public static function callback(): Closure
    {
        return function (Closure|string $class, int|string|null $key = null): Collection {
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
        };
    }

    public static function name(): string
    {
        return 'toDataCollection';
    }
}
