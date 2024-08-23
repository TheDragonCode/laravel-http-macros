<?php

declare(strict_types=1);

namespace DragonCode\LaravelHttpMacros\Commands;

use DragonCode\LaravelHttpMacros\Macros\Macro;
use DragonCode\Support\Facades\Filesystem\Directory;
use DragonCode\Support\Facades\Filesystem\File;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

use function array_map;
use function base_path;
use function collect;
use function config;
use function file_get_contents;
use function implode;
use function is_string;
use function sprintf;

use const PHP_EOL;

class GenerateHelperCommand extends Command
{
    protected $signature = 'http:macros-helper';

    protected $description = 'Generates correct PHPDocs for Http facade macros';

    public function handle(): void
    {
        $names = $this->names();

        $static  = $this->make($names, true);
        $dynamic = $this->make($names);

        $this->cleanUp();
        $this->store($static, true);
        $this->store($dynamic);
    }

    protected function make(array $names, bool $isStatic = false): array
    {
        return array_map(
            fn (string $name) => sprintf(
                '     * @method %s $this %s(\Closure|string $class, int|string|null $key = null)',
                $isStatic ? 'static' : '',
                $name
            ),
            $names
        );
    }

    protected function store(array $methods, bool $isStatic = false): void
    {
        File::store(
            $this->path($this->filename($isStatic)),
            $this->makeDocBlock($methods)
        );
    }

    protected function makeDocBlock(array $methods): string
    {
        return Str::replace('{methods}', implode(PHP_EOL, $methods), $this->template());
    }

    protected function names(): array
    {
        return collect($this->macros())->map(
            fn (Macro|string $macro, int|string $name) => is_string($name) ? $name : $macro::name()
        )->all();
    }

    protected function path(?string $filename = null): string
    {
        return base_path('vendor/_http_macros/' . $filename);
    }

    protected function filename(bool $isStatic): string
    {
        return $isStatic
            ? '_ide_helper_macro_static.php'
            : '_ide_helper_macro.php';
    }

    protected function cleanUp(): void
    {
        Directory::ensureDelete($this->path());
    }

    protected function macros(): array
    {
        return config('http.macros.response', []);
    }

    protected function template(): string
    {
        return file_get_contents(__DIR__ . '/../../stubs/helper.stub');
    }
}
