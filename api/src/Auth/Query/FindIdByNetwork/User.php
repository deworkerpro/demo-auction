<?php

declare(strict_types=1);

namespace App\Auth\Query\FindIdByNetwork;

final readonly class User
{
    public function __construct(
        public string $id
    ) {}
}
