<?php

declare(strict_types=1);

namespace Tests\Fixtures;

class ConstructorData
{
    public function __construct(
        public int $id,
        public string $title,
    ) {}
}
