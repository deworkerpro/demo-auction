<?php

declare(strict_types=1);

namespace App\Auth\Event;

final readonly class UserSignedUp
{
    public function __construct(
        public string $id,
        public string $date,
        public string $email,
    ) {}
}
