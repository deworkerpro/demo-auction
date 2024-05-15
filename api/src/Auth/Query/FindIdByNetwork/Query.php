<?php

declare(strict_types=1);

namespace App\Auth\Query\FindIdByNetwork;

final readonly class Query
{
    public function __construct(
        public string $name,
        public string $identity
    ) {}
}
