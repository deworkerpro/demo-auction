<?php

declare(strict_types=1);

namespace App\Auth\Query\FindIdByCredentials;

final class User
{
    public function __construct(
        public readonly string $id,
        public readonly bool $isActive,
    ) {}
}
