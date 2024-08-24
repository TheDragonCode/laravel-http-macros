<?php

declare(strict_types=1);

function content(string $filename): string
{
    return file_get_contents($filename);
}
