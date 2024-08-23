<?php

declare(strict_types=1);

use Tests\Fixtures\Data\ConstructorData;

test('simple', function () {
    $response = fakeRequest()->get('simple');

    expect($response->toFoo(ConstructorData::class))
        ->toBeInstanceOf(ConstructorData::class)
        ->id->toBe(1)
        ->title->toBe('Qwerty 1');
});

test('single', function () {
    $response = fakeRequest()->get('single');

    expect($response->toFoo(ConstructorData::class, 'result.item'))
        ->toBeInstanceOf(ConstructorData::class)
        ->id->toBe(1)
        ->title->toBe('Qwerty 1');
});

test('many', function () {
    $response = fakeRequest()->get('many');

    expect($response->toFoo(ConstructorData::class, 'result.items.1'))
        ->toBeInstanceOf(ConstructorData::class)
        ->id->toBe(3)
        ->title->toBe('Qwerty 3');
});

test('callback', function () {
    $response = fakeRequest()->get('many');

    expect(
        $response->toFoo(
            fn (array $items) => new ConstructorData(
                $items[0]['id'],
                $items[1]['title'],
            ),
            'result.items'
        )
    )
        ->toBeInstanceOf(ConstructorData::class)
        ->id->toBe(2)
        ->title->toBe('Qwerty 3');
});

test('missing key', function () {
    $response = fakeRequest()->get('simple');

    expect($response->toFoo(ConstructorData::class, 'missing_key'))->toBeNull();
});
