<?php

declare(strict_types=1);

namespace App\Auth\Command\ResetPassword\Reset;

final class Command
{
    public function __construct(
        public readonly string $token = '',
        public readonly string $password = ''
    ) {}
}
