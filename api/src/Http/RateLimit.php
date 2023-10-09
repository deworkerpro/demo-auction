<?php

declare(strict_types=1);

namespace App\Http;

use Redis;

final readonly class RateLimit
{
    public function __construct(private Redis $redis) {}

    public function allowed(string $key, int $limit, int $ttl): bool
    {
        $storageKey = 'rate-' . $key;

        $hits = $this->redis->incr($storageKey);
        $this->redis->expire($storageKey, $ttl);

        if ($hits > $limit) {
            return false;
        }

        return true;
    }
}
