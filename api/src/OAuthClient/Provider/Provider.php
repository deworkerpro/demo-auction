<?php

declare(strict_types=1);

namespace App\OAuthClient\Provider;

interface Provider
{
    public function isFor(string $name): bool;

    public function generateAuthUrl(string $state): string;
}
