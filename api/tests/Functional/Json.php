<?php

declare(strict_types=1);

namespace Test\Functional;

final class Json
{
    public static function decode(string $data): array
    {
        /** @var array */
        return json_decode($data, true, 512, JSON_THROW_ON_ERROR);
    }

    public static function encode(mixed $data): string
    {
        return json_encode($data, JSON_THROW_ON_ERROR);
    }
}
