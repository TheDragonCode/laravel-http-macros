<?php

declare(strict_types=1);

namespace DragonCode\LaravelHttpMacros\Macros;

use Closure;

use function is_callable;
use function is_null;
use function method_exists;

/**
 * Get the JSON decoded body of the response as a class instance.
 */
class ToDataMacro extends Macro
{
    public static function callback(): Closure
    {
        return function (Closure|string $class, int|string|null $key = null): mixed {
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
        };
    }

    public static function name(): string
    {
        return 'toData';
    }
}
