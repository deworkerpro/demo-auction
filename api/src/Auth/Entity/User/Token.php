<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use DateTimeImmutable;
use Webmozart\Assert\Assert;

class Token
{
    private string $value;
    private DateTimeImmutable $expires;

    public function __construct(string $value, DateTimeImmutable $expires)
    {
        Assert::uuid($value);
        $this->value = mb_strtolower($value);
        $this->expires = $expires;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getExpires(): DateTimeImmutable
    {
        return $this->expires;
    }
}
