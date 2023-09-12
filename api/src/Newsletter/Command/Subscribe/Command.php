<?php

declare(strict_types=1);

namespace App\Newsletter\Command\Subscribe;

final readonly class Command
{
    public function __construct(
        public string $id = '',
        public string $email = '',
    ) {}
}
