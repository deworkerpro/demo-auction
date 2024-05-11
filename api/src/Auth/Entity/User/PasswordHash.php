<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use RuntimeException;
use Webmozart\Assert\Assert;

#[ORM\Embeddable]
final readonly class PasswordHash
{
    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $value;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $expires;

    public function __construct(string $value, DateTimeImmutable $expires)
    {
        Assert::notEmpty($value);
        $this->value = $value;
        $this->expires = $expires;
    }

    public function isExpiredTo(DateTimeImmutable $date): bool
    {
        return $this->expires <= $date;
    }

    public function getValue(): string
    {
        return $this->value ?? throw new RuntimeException('Empty value.');
    }

    public function getExpires(): DateTimeImmutable
    {
        return $this->expires ?? throw new RuntimeException('Empty value.');
    }

    /**
     * @internal
     */
    public function isEmpty(): bool
    {
        return $this->value === null;
    }
}
