<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangeEmail\Request;

final class Command
{
    public string $id = '';
    public string $email = '';
}
