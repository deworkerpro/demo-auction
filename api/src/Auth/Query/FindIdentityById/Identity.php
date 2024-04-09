<?php

declare(strict_types=1);

namespace App\Auth\Query\FindIdentityById;

final readonly class Identity
{
    public function __construct(
        public string $id,
        public string $role
    ) {}
}
