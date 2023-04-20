<?php

declare(strict_types=1);

namespace App\Http\Middleware\Auth;

final class Identity
{
    public function __construct(
        public readonly string $id,
        public readonly string $role,
    ) {}
}
