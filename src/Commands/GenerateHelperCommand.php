<?php

declare(strict_types=1);

namespace DragonCode\LaravelHttpMacros\Commands;

use Closure;
use DragonCode\LaravelHttpMacros\Macros\Macro;
use DragonCode\Support\Facades\Filesystem\Directory;
use DragonCode\Support\Facades\Filesystem\File;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionFunction;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionUnionType;

use function array_map;
use function base_path;
use function class_exists;
use function collect;
use function config;
use function file_get_contents;
use function is_numeric;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\outro;
use function sprintf;
use function var_export;

class GenerateHelperCommand extends Command
{
    protected $signature = 'http:macros-helper';

    protected $description = 'Generates correct PHPDocs for Http facade macros';

    protected array $docBlocks = [
        'request' => [
            '     * @method $this %s(%s)',
            '     * @method static $this %s(%s)',
        ],
        'response' => [
            '     * @method mixed %s(%s)',
            '     * @method static mixed %s(%s)',
        ],
    ];

    public function handle(): void
    {
        $this->sections()->map(function (array $macros, string $section) {
            intro($section);

            return $this->macros($macros, $section);
        })
            ->tap(fn () => outro('storing'))
            ->tap(fn () => $this->cleanUp())
            ->each(fn (Collection $blocks, string $section) => $this->store(
                $section,
                $this->compileBlocks($section, $blocks->flatten())
            ));
    }

    protected function cleanUp(): void
    {
        $this->components->task('clean up', fn () => Directory::ensureDelete($this->directory()));
    }

    protected function macros(array $macros, string $section): Collection
    {
        return collect($macros)->map(function (Macro|string $macro, int|string $name) use ($section) {
            $name = $this->resolveName($macro, $name);

            $this->components->task($name, function () use ($macro, $name, $section, &$result) {
                $result = $this->prepare($section, $name, $macro::callback());
            });

            return $result;
        });
    }

    protected function store(string $section, string $content): void
    {
        $this->components->task($section, fn () => File::store($this->helperPath($section), $content));
    }

    protected function compileBlocks(string $section, Collection $blocks): string
    {
        return Str::replace(
            ['{class}', '{methods}'],
            [
                Str::studly($section),
                $blocks->implode("\n"),
            ],
            $this->stub()
        );
    }

    protected function prepare(string $section, string $name, Closure $callback): array
    {
        return $this->docBlock($section, $name, $this->docBlockParameters($callback));
    }

    protected function docBlock(string $section, string $name, string $parameters): array
    {
        return array_map(fn (string $template) => sprintf($template, $name, $parameters), $this->docBlocks[$section]);
    }

    protected function docBlockParameters(Closure $callback): string
    {
        return collect($this->reflectionCallback($callback)->getParameters())
            ->map(function (ReflectionParameter $parameter) {
                $result = $parameter->hasType() ? $this->compactTypes($parameter->getType()) : 'mixed';

                $result .= ' $' . $parameter->getName();

                if ($parameter->isDefaultValueAvailable()) {
                    $result .= ' = ' . var_export($parameter->getDefaultValue(), true);
                }

                return $result;
            })->implode(', ');
    }

    protected function compactTypes(ReflectionNamedType|ReflectionUnionType $type): string
    {
        if ($type instanceof ReflectionNamedType) {
            return class_exists($type->getName()) ? '\\' . $type->getName() : $type->getName();
        }

        return collect($type->getTypes())->map(
            fn (ReflectionNamedType $type) => $this->compactTypes($type)
        )->implode('|');
    }

    protected function reflectionCallback(Closure $callback): ReflectionFunction
    {
        return new ReflectionFunction($callback);
    }

    protected function resolveName(Macro|string $macro, int|string $name): string
    {
        return is_numeric($name) ? $macro::name() : $name;
    }

    protected function sections(): Collection
    {
        return collect(config('http.macros', []));
    }

    protected function helperPath(string $name): string
    {
        return $this->directory() . "/_ide_helper_macro_$name.php";
    }

    protected function directory(): string
    {
        return base_path('vendor/_http_macros');
    }

    protected function stub(): string
    {
        return file_get_contents(__DIR__ . '/../../stubs/helper.stub');
    }
}
