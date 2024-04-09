<?php

declare(strict_types=1);

namespace App\OAuth\Command\LogOut;

final readonly class Command
{
    public function __construct(
        public string $userId
    ) {}
}
