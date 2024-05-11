<?php

declare(strict_types=1);

namespace App\Auth\Service;

use App\Auth\Entity\User\PasswordHash;
use DateInterval;
use DateTimeImmutable;
use Webmozart\Assert\Assert;

final readonly class PasswordHasher
{
    public function __construct(
        private DateInterval $interval,
        private int $memoryCost = PASSWORD_ARGON2_DEFAULT_MEMORY_COST
    ) {}

    public function hash(string $password, DateTimeImmutable $date): PasswordHash
    {
        Assert::notEmpty($password);

        return new PasswordHash(
            password_hash($password, PASSWORD_ARGON2I, ['memory_cost' => $this->memoryCost]),
            $date->add($this->interval)
        );
    }

    public function validate(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
