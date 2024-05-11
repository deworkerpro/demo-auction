<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Service;

use App\Auth\Service\PasswordHasher;
use DateInterval;
use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(PasswordHasher::class)]
final class PasswordHasherTest extends TestCase
{
    public function testHash(): void
    {
        $hasher = new PasswordHasher(new DateInterval('P1M'), 16);

        $hash = $hasher->hash($password = 'new-password', $date = new DateTimeImmutable());

        self::assertNotSame($password, $hash->getValue());
        self::assertEquals($date->add(new DateInterval('P1M')), $hash->getExpires());
    }

    public function testHashEmpty(): void
    {
        $hasher = new PasswordHasher(new DateInterval('P1M'), 16);

        $this->expectException(InvalidArgumentException::class);
        $hasher->hash('', new DateTimeImmutable());
    }

    public function testValidate(): void
    {
        $hasher = new PasswordHasher(new DateInterval('P1M'), 16);

        $hash = $hasher->hash($password = 'new-password', new DateTimeImmutable());

        self::assertTrue($hasher->validate($password, $hash->getValue()));
        self::assertFalse($hasher->validate('wrong-password', $hash->getValue()));
    }
}
