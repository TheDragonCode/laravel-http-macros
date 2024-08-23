<?php

declare(strict_types=1);

use DragonCode\LaravelHttpMacros\Commands\GenerateHelperCommand;
use DragonCode\Support\Facades\Filesystem\Directory;
use DragonCode\Support\Facades\Filesystem\File;

use function Pest\Laravel\artisan;

beforeEach(
    fn () => Directory::ensureDelete(base_path('vendor/_http_macros'))
);

test('new generation', closure: function (string $type, string $name) {
    $directory = base_path('vendor/_http_macros');
    $filename  = $directory . "/_ide_helper_macro$name.php";
    $snapshot  = __DIR__ . "/../../Snapshots/$type";

    expect($directory)->not->toBeDirectory();

    artisan(GenerateHelperCommand::class)->run();

    expect(realpath($filename))->not->toBeFalse()->toBeReadableFile();
    expect(content($filename))->toBe(content($snapshot));
})->with('types');

test('replace', closure: function (string $type, string $name) {
    $directory = base_path('vendor/_http_macros');
    $filename  = $directory . "/_ide_helper_macro$name.php";
    $snapshot  = __DIR__ . "/../../Snapshots/$type";

    expect($directory)->not->toBeDirectory();

    File::store($filename, 'foo');

    artisan(GenerateHelperCommand::class)->run();

    expect(realpath($filename))->not->toBeFalse()->toBeReadableFile();
    expect(content($filename))->toBe(content($snapshot));
})->with('types');

test('clean up', closure: function (string $type, string $name) {
    $directory = base_path('vendor/_http_macros');
    $filename  = $directory . "/_ide_helper_macro$name.php";
    $snapshot  = __DIR__ . "/../../Snapshots/$type";

    expect($directory)->not->toBeDirectory();

    File::store($directory . '/foo.php', 'foo');
    File::store($directory . '/bar.php', 'foo');

    artisan(GenerateHelperCommand::class)->run();

    expect(realpath($filename))->not->toBeFalse()->toBeReadableFile();
    expect(content($filename))->toBe(content($snapshot));

    expect($directory . '/foo.php')->not->toBeFile();
    expect($directory . '/bar.php')->not->toBeFile();
})->with('types');
