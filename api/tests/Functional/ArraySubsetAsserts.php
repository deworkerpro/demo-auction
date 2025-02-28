<?php

declare(strict_types=1);

namespace Test\Functional;

use PHPUnit\Framework\Assert;

trait ArraySubsetAsserts
{
    /**
     * @param non-empty-array<array-key, mixed> $subset
     */
    public static function assertArraySubset(array $subset, array $data, string $message = ''): void
    {
        Assert::assertArrayIsEqualToArrayOnlyConsideringListOfKeys($subset, $data, array_keys($subset), $message);
    }
}
