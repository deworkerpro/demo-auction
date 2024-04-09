<?php

declare(strict_types=1);

namespace App\Auth\Command\ResetPassword\Reset;

final readonly class Command
{
    public function __construct(
        public string $token,
        public string $password
    ) {}
}
