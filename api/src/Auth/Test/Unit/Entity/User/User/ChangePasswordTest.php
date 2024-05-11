<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User\User;

use App\Auth\Entity\User\PasswordHash;
use App\Auth\Entity\User\User;
use App\Auth\Service\PasswordHasher;
use App\Auth\Test\Builder\UserBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(User::class)]
final class ChangePasswordTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())
            ->active()
            ->build();

        $hasher = $this->createHasher([
            ['old-password', 'hash', true],
            ['new-password', 'hash', false],
        ], $hash = 'new-hash');

        $user->changePassword(
            'old-password',
            'new-password',
            new DateTimeImmutable(),
            $hasher
        );

        self::assertSame($hash, $user->getPasswordHash()?->getValue());
    }

    public function testWrongCurrent(): void
    {
        $user = (new UserBuilder())
            ->active()
            ->build();

        $hasher = $this->createHasher([
            ['old-password', 'hash', false],
            ['new-password', 'hash', false],
        ], 'new-hash');

        $this->expectExceptionMessage('Incorrect current password.');
        $user->changePassword(
            'old-password',
            'new-password',
            new DateTimeImmutable(),
            $hasher
        );
    }

    public function testSameNew(): void
    {
        $user = (new UserBuilder())
            ->active()
            ->build();

        $hasher = $this->createHasher([
            ['old-password', 'hash', true],
            ['new-password', 'hash', true],
        ], 'new-hash');

        $this->expectExceptionMessage('New password is already same.');
        $user->changePassword(
            'old-password',
            'new-password',
            new DateTimeImmutable(),
            $hasher
        );
    }

    public function testByNetwork(): void
    {
        $user = (new UserBuilder())
            ->viaNetwork()
            ->build();

        $hasher = $this->createHasher([], 'new-hash');

        $this->expectExceptionMessage('User does not have an old password.');
        $user->changePassword(
            'any-old-password',
            'new-password',
            new DateTimeImmutable(),
            $hasher
        );
    }

    /**
     * @param list<list<mixed>> $validateMap
     */
    private function createHasher(array $validateMap, string $hash): PasswordHasher
    {
        $hasher = $this->createStub(PasswordHasher::class);
        $hasher->method('validate')->willReturnMap($validateMap);
        $hasher->method('hash')->willReturn(new PasswordHash($hash, new DateTimeImmutable('+1 day')));
        return $hasher;
    }
}
