<?php

declare(strict_types=1);

namespace App;

interface AggregateRoot
{
    /**
     * @return object[]
     */
    public function releaseEvents(): array;
}
