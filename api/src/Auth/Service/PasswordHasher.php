<?php

declare(strict_types=1);

namespace App\Auth\Service;

use Webmozart\Assert\Assert;

final readonly class PasswordHasher
{
    public function __construct(private int $memoryCost = PASSWORD_ARGON2_DEFAULT_MEMORY_COST) {}

    public function hash(string $password): string
    {
        Assert::notEmpty($password);

        return password_hash($password, PASSWORD_ARGON2I, ['memory_cost' => $this->memoryCost]);
    }

    public function validate(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
