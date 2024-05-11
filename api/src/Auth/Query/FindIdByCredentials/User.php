<?php

declare(strict_types=1);

namespace App\Auth\Query\FindIdByCredentials;

final readonly class User
{
    public function __construct(
        public string $id,
        public bool $isActive,
        public bool $isPasswordExpired,
    ) {}
}
