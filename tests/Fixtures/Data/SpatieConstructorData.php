<?php

declare(strict_types=1);

namespace Tests\Fixtures\Data;

use Spatie\LaravelData\Data;

class SpatieConstructorData extends Data
{
    public function __construct(
        public int $id,
        public string $title,
    ) {}
}
