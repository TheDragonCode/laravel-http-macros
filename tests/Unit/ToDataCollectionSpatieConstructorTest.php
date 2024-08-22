<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Tests\Fixtures\SpatieConstructorData;

test('many', function () {
    $response = fakeRequest()->get('many');

    expect($response->toDataCollection(SpatieConstructorData::class, 'result.items'))
        ->toBeInstanceOf(Collection::class)
        ->pluck('title', 'id')
        ->all()
        ->toBe([
            2 => 'Qwerty 2',
            3 => 'Qwerty 3',
        ]);
});

test('callback', function () {
    $response = fakeRequest()->get('many');

    expect(
        $response->toDataCollection(
            fn (array $items) => SpatieConstructorData::from([
                'id'    => $items[0]['id'],
                'title' => $items[1]['title'],
            ]),
            'result.items'
        )
    )
        ->toBeInstanceOf(Collection::class)
        ->count()->toBe(1)
        ->first()->id->toBe(2)
        ->first()->title->toBe('Qwerty 3');
});

test('missing key', function () {
    $response = fakeRequest()->get('many');

    expect($response->toDataCollection(SpatieConstructorData::class, 'missing_key'))
        ->toBeInstanceOf(Collection::class)
        ->isEmpty()
        ->toBeTrue();
});
