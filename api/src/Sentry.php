<?php

declare(strict_types=1);

namespace App;

use Sentry\State\HubInterface;
use Throwable;

final class Sentry
{
    private HubInterface $hub;

    public function __construct(HubInterface $hub)
    {
        $this->hub = $hub;
    }

    public function capture(Throwable $exception): void
    {
        $this->hub->captureException($exception);
    }
}
