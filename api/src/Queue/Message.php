<?php

declare(strict_types=1);

namespace App\Queue;

final readonly class Message
{
    public function __construct(
        public int $id,
        public string $type,
        public array $payload,
    ) {}
}
