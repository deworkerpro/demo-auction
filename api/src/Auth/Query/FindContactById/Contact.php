<?php

declare(strict_types=1);

namespace App\Auth\Query\FindContactById;

final class Contact
{
    public function __construct(
        public readonly string $id,
        public readonly string $email
    ) {}
}
