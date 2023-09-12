<?php

declare(strict_types=1);

namespace App\Queue;

use Closure;

interface Consumer
{
    /**
     * @param Closure(Message): void $callback
     */
    public function consume(string $queue, Closure $callback): void;
}
