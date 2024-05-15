<?php

declare(strict_types=1);

namespace App\Auth\Query\FindIdByEmail;

final readonly class Query
{
    public function __construct(
        public string $email
    ) {}
}
