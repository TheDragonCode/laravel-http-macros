<?php

declare(strict_types=1);

namespace DragonCode\LaravelHttpMacros\Macros;

use Closure;

/** @mixin \Illuminate\Http\Client\Response */
abstract class Macro
{
    abstract public static function callback(): Closure;

    abstract public static function name(): string;
}
