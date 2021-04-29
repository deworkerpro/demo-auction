<?php

declare(strict_types=1);

namespace App\Http\Middleware\Auth;

/**
 * @psalm-immutable
 */
final class Identity
{
    public function __construct(
        public string $id,
        public string $role,
    ) {
    }
}
