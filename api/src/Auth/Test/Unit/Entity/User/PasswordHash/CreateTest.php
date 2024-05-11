<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User\PasswordHash;

use App\Auth\Entity\User\PasswordHash;
use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @internal
 */
#[CoversClass(PasswordHash::class)]
final class CreateTest extends TestCase
{
    public function testSuccess(): void
    {
        $hash = new PasswordHash(
            $value = Uuid::uuid4()->toString(),
            $expires = new DateTimeImmutable()
        );

        self::assertSame($value, $hash->getValue());
        self::assertEquals($expires, $hash->getExpires());
    }

    public function testEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new PasswordHash('', new DateTimeImmutable());
    }
}
