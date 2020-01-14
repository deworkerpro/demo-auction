<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User\User\ChangeEmail;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Token;
use App\Auth\Test\Builder\UserBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @covers User
 */
class ConfirmTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now = new DateTimeImmutable();
        $token = new Token(Uuid::uuid4()->toString(), $now->modify('+1 day'));

        $user->requestEmailChanging($token, $now, $new = new Email('new-email@app.test'));

        self::assertNotNull($user->getNewEmailToken());

        $user->confirmEmailChanging($token->getValue(), $now);

        self::assertNull($user->getNewEmail());
        self::assertNull($user->getNewEmailToken());
        self::assertEquals($new, $user->getEmail());
    }

    public function testInvalidToken(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now = new DateTimeImmutable();
        $token = new Token(Uuid::uuid4()->toString(), $now->modify('+1 day'));

        $user->requestEmailChanging($token, $now, new Email('new-email@app.test'));

        $this->expectExceptionMessage('Token is invalid.');
        $user->confirmEmailChanging('invalid', $now);
    }

    public function testExpiredToken(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now = new DateTimeImmutable();
        $token = new Token(Uuid::uuid4()->toString(), $now);

        $user->requestEmailChanging($token, $now, new Email('new-email@app.test'));

        $this->expectExceptionMessage('Token is expired.');
        $user->confirmEmailChanging($token->getValue(), $now->modify('+1 day'));
    }

    public function testNotRequested(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now = new DateTimeImmutable();
        $token = new Token(Uuid::uuid4()->toString(), $now->modify('+1 day'));

        $this->expectExceptionMessage('Changing is not requested.');
        $user->confirmEmailChanging($token->getValue(), $now);
    }
}
