<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangeEmail\Request;

final class Command
{
    public function __construct(
        public readonly string $id = '',
        public readonly string $email = ''
    ) {}
}
