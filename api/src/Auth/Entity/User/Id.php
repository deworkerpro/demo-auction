<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use Override;
use Ramsey\Uuid\Uuid;
use Stringable;
use Webmozart\Assert\Assert;

final class Id implements Stringable
{
    private string $value;

    public function __construct(string $value)
    {
        Assert::uuid($value);
        $this->value = mb_strtolower($value);
    }

    #[Override]
    public function __toString(): string
    {
        return $this->getValue();
    }

    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
