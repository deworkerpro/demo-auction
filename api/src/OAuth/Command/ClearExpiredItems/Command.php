<?php

declare(strict_types=1);

namespace App\OAuth\Command\ClearExpiredItems;

final class Command
{
    public string $date;

    public function __construct(string $date)
    {
        $this->date = $date;
    }
}
