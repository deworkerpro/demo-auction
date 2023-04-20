<?php

declare(strict_types=1);

namespace App\Auth\Command\AttachNetwork;

final class Command
{
    public function __construct(
        public readonly string $id = '',
        public readonly string $network = '',
        public readonly string $identity = ''
    ) {}
}
