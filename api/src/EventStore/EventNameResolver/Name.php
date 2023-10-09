<?php

declare(strict_types=1);

namespace App\EventStore\EventNameResolver;

final readonly class Name
{
    public function __construct(
        public string $class,
        public string $name
    ) {}
}
