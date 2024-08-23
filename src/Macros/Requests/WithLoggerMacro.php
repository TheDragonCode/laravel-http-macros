<?php

declare(strict_types=1);

namespace DragonCode\LaravelHttpMacros\Macros\Requests;

use Closure;
use DragonCode\LaravelHttpMacros\Macros\Macro;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Illuminate\Http\Client\PendingRequest;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

use function call_user_func;
use function config;
use function storage_path;

/**
 * Adds the ability to log HTTP requests and responses.
 */
class WithLoggerMacro extends Macro
{
    public static function callback(): Closure
    {
        return function (string $channel): PendingRequest {
            $config = config('logging.channels.' . $channel);

            $handler   = call_user_func([$config['handler'] ?? HandlerStack::class, 'create']);
            $formatter = $config['formatter'] ?? MessageFormatter::class;

            $path = $config['path'] ?? storage_path('logs/laravel.log');

            $logger = (new Logger($channel))->pushHandler(
                new StreamHandler($path)
            );

            $handler->push(
                Middleware::log($logger, new $formatter())
            );

            return $this->setHandler($handler);
        };
    }

    public static function name(): string
    {
        return 'withLogger';
    }
}
