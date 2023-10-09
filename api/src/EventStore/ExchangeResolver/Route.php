<?php

declare(strict_types=1);

namespace App\EventStore\ExchangeResolver;

final readonly class Route
{
    public function __construct(
        public string $event,
        public string $exchange
    ) {}
}
