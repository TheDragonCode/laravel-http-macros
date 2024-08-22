<?php

declare(strict_types=1);

use Illuminate\Http\Client\Factory;

function fakeRequest(): Factory
{
    return (new Factory())->fake([
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
        'many'   => [
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
