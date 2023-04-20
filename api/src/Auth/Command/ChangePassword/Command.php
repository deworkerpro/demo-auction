<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangePassword;

final class Command
{
    public function __construct(
        public readonly string $id = '',
        public readonly string $current = '',
        public readonly string $new = ''
    ) {}
}
