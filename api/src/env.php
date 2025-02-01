<?php

declare(strict_types=1);

namespace App;

use RuntimeException;

function env(string $name, ?string $default = null): string
{
    $value = getenv($name);

    if ($value !== false) {
        return $value;
    }

    $file = getenv($name . '_FILE');

    if ($file !== false) {
        $content = file_get_contents($file);

        if ($content === false) {
            throw new RuntimeException('Unable to open "' . $file . '" file');
        }

        return trim($content);
    }

    if ($default !== null) {
        return $default;
    }

    throw new RuntimeException('Undefined env ' . $name);
}
