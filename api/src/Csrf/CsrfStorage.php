<?php

declare(strict_types=1);

namespace App\Csrf;

use ArrayAccess;
use Countable;
use Iterator;
use Redis;

/**
 * @implements ArrayAccess<string, string>
 * @implements Iterator<string, string>
 */
final readonly class CsrfStorage implements ArrayAccess, Countable, Iterator
{
    public function __construct(private Redis $redis) {}

    public function current(): mixed
    {
        return null;
    }

    public function next(): void {}

    public function key(): mixed
    {
        return null;
    }

    public function valid(): bool
    {
        return false;
    }

    public function rewind(): void {}

    public function offsetExists(mixed $offset): bool
    {
        return $this->redis->exists($offset) > 0;
    }

    public function offsetGet(mixed $offset): mixed
    {
        /**
         * @var false|mixed|string $value
         */
        $value = $this->redis->get($offset);

        return (string)$value;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->redis->set((string)$offset, $value, ['nx', 'ex' => 3600]);
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->redis->del($offset);
    }

    public function count(): int
    {
        return 0;
    }
}
