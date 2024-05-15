<?php

declare(strict_types=1);

namespace App\OAuthClient\Provider;

use App\OAuthClient\Identity;

interface Provider
{
    public function isFor(string $name): bool;

    public function generateAuthUrl(string $state): string;

    public function getIdentity(string $code): Identity;
}
