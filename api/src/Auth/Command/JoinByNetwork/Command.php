<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByNetwork;

final class Command
{
    public string $email = '';
    public string $network = '';
    public string $identity = '';
}
