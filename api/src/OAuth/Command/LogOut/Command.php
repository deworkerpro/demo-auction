<?php

declare(strict_types=1);

namespace App\OAuth\Command\LogOut;

final class Command
{
    public function __construct(
        public readonly string $userId
    ) {}
}
