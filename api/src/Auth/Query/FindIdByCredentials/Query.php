<?php

declare(strict_types=1);

namespace App\Auth\Query\FindIdByCredentials;

final class Query
{
    public string $email = '';
    public string $password = '';
}
