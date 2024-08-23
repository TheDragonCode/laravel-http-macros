<?php

declare(strict_types=1);

use Tests\Fixtures\Data\FromMethodData;

test('simple', function () {
    $response = fakeRequest()->get('simple');

    expect($response->toData(FromMethodData::class))
        ->toBeInstanceOf(FromMethodData::class)
        ->id->toBe(1)
        ->title->toBe('Qwerty 1');
});

test('single', function () {
    $response = fakeRequest()->get('single');

    expect($response->toData(FromMethodData::class, 'result.item'))
        ->toBeInstanceOf(FromMethodData::class)
        ->id->toBe(1)
        ->title->toBe('Qwerty 1');
});

test('many', function () {
    $response = fakeRequest()->get('many');

    expect($response->toData(FromMethodData::class, 'result.items.1'))
        ->toBeInstanceOf(FromMethodData::class)
        ->id->toBe(3)
        ->title->toBe('Qwerty 3');
});

test('callback', function () {
    $response = fakeRequest()->get('many');

    expect(
        $response->toData(
            fn (array $items) => new FromMethodData(
                $items[0]['id'],
                $items[1]['title'],
            ),
            'result.items'
        )
    )
        ->toBeInstanceOf(FromMethodData::class)
        ->id->toBe(2)
        ->title->toBe('Qwerty 3');
});

test('missing key', function () {
    $response = fakeRequest()->get('simple');

    expect($response->toData(FromMethodData::class, 'missing_key'))->toBeNull();
});
