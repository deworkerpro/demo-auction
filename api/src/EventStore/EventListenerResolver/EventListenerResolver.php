<?php

declare(strict_types=1);

namespace App\EventStore\EventListenerResolver;

use Closure;
use Psr\Container\ContainerInterface;
use RuntimeException;

final readonly class EventListenerResolver
{
    /**
     * @param Closure():ContainerInterface $containerFactory
     * @param Listener[] $listeners
     */
    public function __construct(private Closure $containerFactory, private array $listeners) {}

    public function resolve(string $queue, object $event): callable
    {
        $container = ($this->containerFactory)();

        foreach ($this->listeners as $listener) {
            if ($listener->queue === $queue && $listener->event === $event::class) {
                $handler = $container->get($listener->listener);

                if (!\is_callable($handler)) {
                    throw new RuntimeException('Incorrect listener type ' . $listener->listener);
                }

                return $handler;
            }
        }

        return static function (): void {};
    }
}
