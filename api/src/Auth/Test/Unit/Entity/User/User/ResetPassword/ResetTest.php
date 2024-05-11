<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User\User\ResetPassword;

use App\Auth\Entity\User\PasswordHash;
use App\Auth\Entity\User\Token;
use App\Auth\Entity\User\User;
use App\Auth\Service\PasswordHasher;
use App\Auth\Test\Builder\UserBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @internal
 */
#[CoversClass(User::class)]
final class ResetTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())->active()->build();

        $hasher = $this->createHasher($password = 'new-password', $hash = 'new-hash');

        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 hour'));

        $user->requestPasswordReset($token, $now);

        $user->resetPassword($token->getValue(), $now, $password, $hasher);

        self::assertNull($user->getPasswordResetToken());
        self::assertSame($hash, $user->getPasswordHash()?->getValue());
    }

    public function testInvalidToken(): void
    {
        $user = (new UserBuilder())->active()->build();

        $hasher = $this->createHasher($password = 'new-password', 'new-hash');

        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 hour'));

        $user->requestPasswordReset($token, $now);

        $this->expectExceptionMessage('Token is invalid.');
        $user->resetPassword(Uuid::uuid4()->toString(), $now, $password, $hasher);
    }

    public function testExpiredToken(): void
    {
        $user = (new UserBuilder())->active()->build();

        $hasher = $this->createHasher($password = 'new-password', 'new-hash');

        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 hour'));

        $user->requestPasswordReset($token, $now);

        $this->expectExceptionMessage('Token is expired.');
        $user->resetPassword($token->getValue(), $now->modify('+1 day'), $password, $hasher);
    }

    public function testNotRequested(): void
    {
        $user = (new UserBuilder())->active()->build();

        $hasher = $this->createHasher($password = 'new-password', 'new-hash');

        $now = new DateTimeImmutable();

        $this->expectExceptionMessage('Resetting is not requested.');
        $user->resetPassword(Uuid::uuid4()->toString(), $now, $password, $hasher);
    }

    private function createHasher(string $password, string $hash): PasswordHasher
    {
        $hasher = $this->createMock(PasswordHasher::class);
        $hasher->method('hash')->with($password)->willReturn(new PasswordHash($hash, new DateTimeImmutable('+1 day')));
        return $hasher;
    }

    private function createToken(DateTimeImmutable $date): Token
    {
        return new Token(
            Uuid::uuid4()->toString(),
            $date
        );
    }
}
