<?php

declare(strict_types=1);

namespace App;

use Sentry\State\HubInterface;
use Throwable;

final readonly class Sentry
{
    public function __construct(private HubInterface $hub) {}

    public function capture(Throwable $exception): void
    {
        $this->hub->captureException($exception);
    }
}
