<?php

declare(strict_types=1);

namespace App\Auth\Command\Remove;

final class Command
{
    public function __construct(
        public readonly string $id = ''
    ) {}
}
