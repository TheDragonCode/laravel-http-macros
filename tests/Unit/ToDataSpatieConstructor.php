<?php

declare(strict_types=1);

use Tests\Fixtures\Data\SpatieConstructorData;

test('simple', function () {
    $response = fakeRequest()->get('simple');

    expect($response->toData(SpatieConstructorData::class))
        ->toBeInstanceOf(SpatieConstructorData::class)
        ->id->toBe(1)
        ->title->toBe('Qwerty 1');
});

test('single', function () {
    $response = fakeRequest()->get('single');

    expect($response->toData(SpatieConstructorData::class, 'result.item'))
        ->toBeInstanceOf(SpatieConstructorData::class)
        ->id->toBe(1)
        ->title->toBe('Qwerty 1');
});

test('many', function () {
    $response = fakeRequest()->get('many');

    expect($response->toData(SpatieConstructorData::class, 'result.items.1'))
        ->toBeInstanceOf(SpatieConstructorData::class)
        ->id->toBe(3)
        ->title->toBe('Qwerty 3');
});

test('callback', function () {
    $response = fakeRequest()->get('many');

    expect(
        $response->toData(
            fn (array $items) => new SpatieConstructorData(
                $items[0]['id'],
                $items[1]['title'],
            ),
            'result.items'
        )
    )
        ->toBeInstanceOf(SpatieConstructorData::class)
        ->id->toBe(2)
        ->title->toBe('Qwerty 3');
});

test('missing key', function () {
    $response = fakeRequest()->get('simple');

    expect($response->toData(SpatieConstructorData::class, 'missing_key'))->toBeNull();
});
