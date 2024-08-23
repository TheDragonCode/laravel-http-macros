<?php

declare(strict_types=1);

use DragonCode\LaravelHttpMacros\Macros\Requests\WithLoggerMacro;
use DragonCode\LaravelHttpMacros\Macros\Responses\ToDataCollectionMacro;
use DragonCode\LaravelHttpMacros\Macros\Responses\ToDataMacro;

return [
    'macros' => [
        'request' => [
            WithLoggerMacro::class,

            // CustomMacro::class,
            // 'toFoo' => CustomMacro::class,
        ],

        'response' => [
            ToDataMacro::class,
            ToDataCollectionMacro::class,

            // CustomMacro::class,
            // 'toFoo' => CustomMacro::class,
        ],
    ],
];
