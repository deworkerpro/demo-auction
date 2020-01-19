<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Service;

use App\Auth\Service\PasswordHasher;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers PasswordHasher
 */
class PasswordHasherTest extends TestCase
{
    public function testHash(): void
    {
        $hasher = new PasswordHasher();

        $hash = $hasher->hash($password = 'new-password');

        self::assertNotEmpty($hash);
        self::assertNotEquals($password, $hash);
    }

    public function testHashEmpty(): void
    {
        $hasher = new PasswordHasher();

        $this->expectException(InvalidArgumentException::class);
        $hasher->hash('');
    }

    public function testValidate(): void
    {
        $hasher = new PasswordHasher();

        $hash = $hasher->hash($password = 'new-password');

        self::assertTrue($hasher->validate($password, $hash));
        self::assertFalse($hasher->validate('wrong-password', $hash));
    }
}
