<?php

declare(strict_types=1);

namespace App\EventStore\ExchangeResolver;

use RuntimeException;

final readonly class ExchangeResolver
{
    /**
     * @param Route[] $routes
     */
    public function __construct(private array $routes) {}

    public function resolveForEvent(string $type): string
    {
        foreach ($this->routes as $route) {
            if ($route->event === $type) {
                return $route->exchange;
            }
        }

        throw new RuntimeException('Unable to route event "' . $type . '"');
    }
}
