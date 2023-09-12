<?php

declare(strict_types=1);

namespace App\EventStore\EventListenerResolver;

final readonly class Listener
{
    public function __construct(
        public string $queue,
        public string $event,
        public string $listener
    ) {}
}
