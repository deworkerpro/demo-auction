<?php

declare(strict_types=1);

namespace App;

use Sentry\State\HubInterface;
use Throwable;

final class Sentry
{
    public function __construct(private readonly HubInterface $hub) {}

    public function capture(Throwable $exception): void
    {
        $this->hub->captureException($exception);
    }
}
