<?php

declare(strict_types=1);

namespace App\OAuth\Command\LogOut;

final class Command
{
    public string $userId;

    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }
}
