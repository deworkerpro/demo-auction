<?php

declare(strict_types=1);

namespace App\Csrf;

use ArrayAccess;
use Countable;
use Iterator;
use Override;
use Redis;

/**
 * @implements ArrayAccess<string, string>
 * @implements Iterator<string, string>
 */
final readonly class CsrfStorage implements ArrayAccess, Countable, Iterator
{
    public function __construct(private Redis $redis) {}

    #[Override]
    public function current(): mixed
    {
        return null;
    }

    #[Override]
    public function next(): void {}

    #[Override]
    public function key(): mixed
    {
        return null;
    }

    #[Override]
    public function valid(): bool
    {
        return false;
    }

    #[Override]
    public function rewind(): void {}

    #[Override]
    public function offsetExists(mixed $offset): bool
    {
        return $this->redis->exists($offset) > 0;
    }

    #[Override]
    public function offsetGet(mixed $offset): mixed
    {
        /**
         * @var false|mixed|string $value
         */
        $value = $this->redis->get($offset);

        return (string)$value;
    }

    #[Override]
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->redis->set((string)$offset, $value, ['nx', 'ex' => 3600]);
    }

    #[Override]
    public function offsetUnset(mixed $offset): void
    {
        $this->redis->del($offset);
    }

    #[Override]
    public function count(): int
    {
        return 0;
    }
}
