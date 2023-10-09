<?php

declare(strict_types=1);

namespace App\EventStore\EventNameResolver;

use RuntimeException;

final readonly class EventNameResolver
{
    /**
     * @param Name[] $names
     */
    public function __construct(private array $names) {}

    public function nameForClass(string $class): string
    {
        foreach ($this->names as $item) {
            if ($item->class === $class) {
                return $item->name;
            }
        }

        throw new RuntimeException('Unable to resolve type for event "' . $class . '"');
    }

    public function classForName(string $name): string
    {
        foreach ($this->names as $item) {
            if ($item->name === $name) {
                return $item->class;
            }
        }

        throw new RuntimeException('Unable to resolve class for event "' . $name . '"');
    }
}
