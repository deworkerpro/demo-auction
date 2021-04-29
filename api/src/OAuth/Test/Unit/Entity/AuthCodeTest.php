<?php

declare(strict_types=1);

namespace App\OAuth\Test\Unit\Entity;

use App\OAuth\Entity\AuthCode;
use App\OAuth\Entity\Scope;
use App\OAuth\Test\Builder\ClientBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @internal
 */
final class AuthCodeTest extends TestCase
{
    public function testCreate(): void
    {
        $code = new AuthCode();

        $code->setClient($client = (new ClientBuilder())->build());
        $code->addScope($scope = new Scope('common'));
        $code->setIdentifier($identifier = Uuid::uuid4()->toString());
        $code->setUserIdentifier($userIdentifier = Uuid::uuid4()->toString());
        $code->setExpiryDateTime($expiryDateTime = new DateTimeImmutable());
        $code->setRedirectUri($redirectUri = 'http://localhost/auth');

        self::assertSame($client, $code->getClient());
        self::assertSame([$scope], $code->getScopes());
        self::assertSame($identifier, $code->getIdentifier());
        self::assertSame($userIdentifier, $code->getUserIdentifier());
        self::assertSame($expiryDateTime, $code->getExpiryDateTime());
        self::assertSame($redirectUri, $code->getRedirectUri());
    }
}
