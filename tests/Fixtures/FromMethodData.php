<?php

declare(strict_types=1);

namespace Tests\Fixtures;

class FromMethodData
{
    public function __construct(
        public int $id,
        public string $title,
    ) {}

    public static function from(array $data): static
    {
        return new static(...$data);
    }
}
