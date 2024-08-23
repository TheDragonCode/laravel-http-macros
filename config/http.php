<?php

declare(strict_types=1);

use DragonCode\LaravelHttpMacros\Macros\ToDataCollectionMacro;
use DragonCode\LaravelHttpMacros\Macros\ToDataMacro;
use DragonCode\LaravelHttpMacros\Macros\Responses\ToDataCollectionMacro;
use DragonCode\LaravelHttpMacros\Macros\Responses\ToDataMacro;

return [
    'macros' => [
        'request' => [
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
