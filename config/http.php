<?php

declare(strict_types=1);

use DragonCode\LaravelHttpMacros\Macros\ToDataCollectionMacro;
use DragonCode\LaravelHttpMacros\Macros\ToDataMacro;

return [
    'macros' => [
        'response' => [
            ToDataMacro::class,
            ToDataCollectionMacro::class,

            // CustomMacro::class,
            // 'toFoo' => CustomMacro::class,
        ],
    ],
];
