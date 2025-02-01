<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use Override;
use Ramsey\Uuid\Uuid;
use Stringable;
use Webmozart\Assert\Assert;

final readonly class Id implements Stringable
{
    /**
     * @var non-empty-string
     */
    private string $value;

    public function __construct(string $value)
    {
        Assert::notEmpty($value);
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

    /**
     * @return non-empty-string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
