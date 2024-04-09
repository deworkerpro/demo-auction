<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangePassword;

final readonly class Command
{
    public function __construct(
        public string $id = '',
        public string $current = '',
        public string $new = ''
    ) {}
}
