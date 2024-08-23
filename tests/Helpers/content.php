<?php

declare(strict_types=1);

function content(string $filename): string
{
    return trim(file_get_contents($filename));
}
