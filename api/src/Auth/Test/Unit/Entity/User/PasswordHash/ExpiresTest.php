<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User\PasswordHash;

use App\Auth\Entity\User\PasswordHash;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @internal
 */
#[CoversClass(PasswordHash::class)]
#[CoversFunction('isExpiredTo')]
final class ExpiresTest extends TestCase
{
    public function testNot(): void
    {
        $hash = new PasswordHash(
            Uuid::uuid4()->toString(),
            $expires = new DateTimeImmutable()
        );

        self::assertFalse($hash->isExpiredTo($expires->modify('-1 secs')));
        self::assertTrue($hash->isExpiredTo($expires));
        self::assertTrue($hash->isExpiredTo($expires->modify('+1 secs')));
    }
}
