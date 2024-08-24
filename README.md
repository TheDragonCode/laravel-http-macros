# HTTP Macros for Laravel

![the dragon code laravel http macros](https://preview.dragon-code.pro/the-dragon-code/http-macros.svg?brand=laravel&mode=dark)

[![Stable Version][badge_stable]][link_packagist]
[![Total Downloads][badge_downloads]][link_packagist]
[![Github Workflow Status][badge_build]][link_build]
[![License][badge_license]][link_license]

## Installation

To get the latest version of `HTTP Macros`, simply require the project using [Composer](https://getcomposer.org):

```Bash
composer require dragon-code/laravel-http-macros
```

## Configuration

If desired, you can publish the configuration file using the console command:

```bash
php artisan vendor:publish --provider="DragonCode\\LaravelHttpMacros\\ServiceProvider"
```

If your application already has a `config/http.php` file, then you can simply add a new `macros` key from the
[configuration](config/http.php) file to it.

Here you can specify a list of your classes for registering macros.
Macro classes must inherit from the abstract class `DragonCode\LaravelHttpMacros\Macros\Macro`.

You can also redefine macro names using an associative array. For example:

```php
// Config
return [
    'macros' => [
        'request' => [
            WithLoggerMacro::class,
        ],
        'response' => [
            ToDataMacro::class,
        ],
    ],
];

// Macro
Http::withLogger('some')->get();
Http::withLogger('some')->get()->toData(...);
Http::get()->toData(...);
```
```php
// Config
return [
    'macros' => [
        'request' => [
            'qwerty' => WithLoggerMacro::class,
        ],
        'response' => [
            'qwerty' => ToDataMacro::class,
        ],
    ],
];

// Macro
Http::qwerty('some')->get();
Http::qwerty('some')->get()->qwerty(...);
Http::qwerty('some')->get()->toData(...); // method not found

Http::get()->qwerty(...);
Http::get()->toData(...); // method not found
```

> Note
>
> Please note that IDE hints will not work in this case.

## Usage

### Available Methods

#### Request

- [withLogger](#withlogger)

#### Response

- [toData](#todata)
- [toDataCollection](#todatacollection)

### Method Listing

#### withLogger()

Adds the ability to log HTTP requests and responses.

```php
use Illuminate\Support\Facades\Http;

Http::withLogger('some_channel')->get();
```

This method will log HTTP requests and responses.

It is also possible to use your own handler, message formatting and path to the log file.
To do this, you need to specify the desired channel name from the log file and define the necessary parameters in it.

For example:

```php
// config/logging.php
return [
    // ...
    
    'channels' => [
        'some' => [
            'driver' => 'single',
            'level' => env('LOG_LEVEL', 'debug'),
            'path' => storage_path('logs/some.log'),
            'handler' => \App\Logging\SomeHandlerStack::class,
            'formatter' => \App\Logging\MessageFormatter::class,
        ],
    ],
];

// Usage
return Http::withLogger('some')->...
```

#### toData()

The class instance will be returned.

```php
use Illuminate\Support\Facades\Http;

// Returns a SomeData object
return Http::get()->toData(SomeData::class);

// Will return a SomeData object generated from the JSON path
return Http::get()->toData(SomeData::class, 'data.item');

// Returns the result of the callback execution
return Http::get()->toData(
    fn (array $data) => new SomeData(
        $data['data']['item']['id'],
        $data['data']['item']['title']
    )
);

// Returns the result of the callback execution from a custom JSON path
return Http::get()->toData(
    fn (array $data) => new SomeData($data['id'], $data['title']),
    'data.item'
);
```

> Note
>
> If a `from` method exists in a class, then it will be called to construct the object.
>
> Compatible with [Spatie Laravel Data](https://spatie.be/docs/laravel-data).

```php
class SomeData
{
    public function __construct(
        public int $id,
        public string $title
    ) {}
    
    public static function from(array $data): static
    {
        return new static(...$data);
    }
}

return Http::get()->toData(SomeData::class);
```

#### toDataCollection()

The `Illuminate\Support\Collection` object or an object inherited from it will be returned.

```php
use Illuminate\Support\Facades\Http;

// Returns a collection of SomeData objects
return Http::get()->toDataCollection(SomeData::class);

// Returns a collection of SomeData objects formed from the JSON path
return Http::get()->toDataCollection(SomeData::class, 'data.item');

// Returns the result of the callback execution
return Http::get()->toDataCollection(
    fn (array $data) => collect([
        new SomeData(
            $data['data']['item']['id'],
            $data['data']['item']['title']
        ),
    ])
);

// Returns the result of the callback execution from a custom JSON path
return Http::get()->toDataCollection(
    fn (array $data) => collect([
        new SomeData(...$data),
    ]),
    'data.item'
);
```

> Note
>
> If a `collect` method exists in a class, then it will be called to construct the collection.
>
> Compatible with [Spatie Laravel Data](https://spatie.be/docs/laravel-data).

```php
use Illuminate\Support\Collection;

class SomeData
{
    public function __construct(
        public int $id,
        public string $title
    ) {}
    
    public static function collect(array $items): Collection
    {
        return collect($items)->map(
            fn (array $item) => new static(...$item)
        );
    }
}

return Http::get()->toDataCollection(SomeData::class);
```

### Generate IDE Helper files

You can generate helper files for the IDE using the console command:

```Bash
php artisan http:macros-helper
```

This will help your IDE suggest methods.

![IDE Helper](.github/images/ide-helper.png)

## License

This package is licensed under the [MIT License](LICENSE).


[badge_build]:          https://img.shields.io/github/actions/workflow/status/TheDragonCode/laravel-http-macros/phpunit.yml?style=flat-square

[badge_downloads]:      https://img.shields.io/packagist/dt/dragon-code/laravel-http-macros.svg?style=flat-square

[badge_license]:        https://img.shields.io/packagist/l/dragon-code/laravel-http-macros.svg?style=flat-square

[badge_stable]:         https://img.shields.io/github/v/release/TheDragonCode/laravel-http-macros?label=packagist&style=flat-square

[link_build]:           https://github.com/TheDragonCode/laravel-http-macros/actions

[link_license]:         LICENSE

[link_packagist]:       https://packagist.org/packages/dragon-code/laravel-http-macros
