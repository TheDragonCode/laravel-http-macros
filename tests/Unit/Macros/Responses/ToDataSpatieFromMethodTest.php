<?php

declare(strict_types=1);

use Tests\Fixtures\Data\SpatiePropertiesData;

test('simple', function () {
    $response = fakeRequest()->get('simple');

    expect($response->toData(SpatiePropertiesData::class))
        ->toBeInstanceOf(SpatiePropertiesData::class)
        ->id->toBe(1)
        ->title->toBe('Qwerty 1');
});

test('single', function () {
    $response = fakeRequest()->get('single');

    expect($response->toData(SpatiePropertiesData::class, 'result.item'))
        ->toBeInstanceOf(SpatiePropertiesData::class)
        ->id->toBe(1)
        ->title->toBe('Qwerty 1');
});

test('many', function () {
    $response = fakeRequest()->get('many');

    expect($response->toData(SpatiePropertiesData::class, 'result.items.1'))
        ->toBeInstanceOf(SpatiePropertiesData::class)
        ->id->toBe(3)
        ->title->toBe('Qwerty 3');
});

test('callback', function () {
    $response = fakeRequest()->get('many');

    expect(
        $response->toData(
            fn (array $items) => SpatiePropertiesData::from([
                'id'    => $items[0]['id'],
                'title' => $items[1]['title'],
            ]),
            'result.items'
        )
    )
        ->toBeInstanceOf(SpatiePropertiesData::class)
        ->id->toBe(2)
        ->title->toBe('Qwerty 3');
});

test('missing key', function () {
    $response = fakeRequest()->get('simple');

    expect($response->toData(SpatiePropertiesData::class, 'missing_key'))->toBeNull();
});
