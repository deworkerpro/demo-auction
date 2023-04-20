<?php

declare(strict_types=1);

namespace App\Auth\Query\FindIdentityById;

final class Identity
{
    public function __construct(
        public readonly string $id,
        public readonly string $role
    ) {}
}
