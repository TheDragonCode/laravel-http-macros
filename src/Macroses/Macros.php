<?php

declare(strict_types=1);

namespace DragonCode\LaravelHttpMacros\Macroses;

use Closure;

/** @mixin \Illuminate\Http\Client\Response */
abstract class Macros
{
    abstract public static function callback(): Closure;

    abstract public static function name(): string;
}
