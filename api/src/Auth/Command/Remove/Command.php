<?php

declare(strict_types=1);

namespace App\Auth\Command\Remove;

final readonly class Command
{
    public function __construct(
        public string $id
    ) {}
}
