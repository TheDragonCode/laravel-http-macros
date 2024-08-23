<?php

declare(strict_types=1);

use DragonCode\Support\Facades\Filesystem\File;

beforeEach(
    fn () => File::ensureDelete([
        storage_path('logs/laravel.log'),
        storage_path('logs/foo.log'),
        storage_path('logs/bar.log'),
    ])
);

test('default formatter', function () {
    expect(storage_path('logs/foo.log'))->not->toBeFile();

    fakeRequest()->withLogger('foo')->get('simple');

    expect(storage_path('logs/foo.log'))->toBeFile();

    expect(content(storage_path('logs/foo.log')))->toContain(
        sprintf('AAA: BBB:')
    );
});

test('custom formatter', function () {
    expect(storage_path('logs/bar.log'))->not->toBeFile();

    fakeRequest()->withLogger('bar')->get('simple');

    expect(storage_path('logs/bar.log'))->toBeFile();

    expect(content(storage_path('logs/bar.log')))->toContain(
        sprintf('REQUEST: simple RESPONSE: {"id":1,"title":"Qwerty 1"}')
    );
});

test('unknown channel', function () {
    expect(storage_path('logs/laravel.log'))->not->toBeFile();

    fakeRequest()->withLogger('missing')->get('simple');

    expect(storage_path('logs/laravel.log'))->toBeFile();

    expect(content(storage_path('logs/laravel.log')))->toContain(
        sprintf('CCC: DDD:')
    );
});
