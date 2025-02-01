<?php

declare(strict_types=1);

namespace App\OAuth\Generator;

use DateTimeImmutable;

final class Params
{
    /**
     * @param non-empty-string $userId
     * @param non-empty-string $role
     */
    public function __construct(
        public string $userId,
        public string $role,
        public DateTimeImmutable $expires,
    ) {}
}
