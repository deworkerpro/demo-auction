<?php

declare(strict_types=1);

namespace App\Newsletter\Query\Subscription;

final class Subscription
{
    public function __construct(
        public readonly string $id,
        public readonly string $email
    ) {}
}
