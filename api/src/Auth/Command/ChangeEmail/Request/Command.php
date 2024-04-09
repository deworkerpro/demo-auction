<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangeEmail\Request;

final readonly class Command
{
    public function __construct(
        public string $id = '',
        public string $email = ''
    ) {}
}
