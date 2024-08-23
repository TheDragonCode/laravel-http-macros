<?php

declare(strict_types=1);

use Illuminate\Http\Client\Factory;
use Illuminate\Support\Facades\Http;

function fakeRequest(): Factory
{
    return Http::fake([
        'simple' => [
            'id'    => 1,
            'title' => 'Qwerty 1',
        ],
        'single' => [
            'result' => [
                'item' => [
                    'id'    => 1,
                    'title' => 'Qwerty 1',
                ],
            ],
        ],
        'many' => [
            'result' => [
                'items' => [
                    [
                        'id'    => 2,
                        'title' => 'Qwerty 2',
                    ],
                    [
                        'id'    => 3,
                        'title' => 'Qwerty 3',
                    ],
                ],
            ],
        ],
    ]);
}
