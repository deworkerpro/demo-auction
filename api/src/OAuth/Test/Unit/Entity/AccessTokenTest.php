<?php

declare(strict_types=1);

namespace App\OAuth\Test\Unit\Entity;

use App\OAuth\Entity\AccessToken;
use App\OAuth\Entity\Scope;
use App\OAuth\Test\Builder\ClientBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @internal
 */
final class AccessTokenTest extends TestCase
{
    public function testCreate(): void
    {
        $token = new AccessToken();

        $token->setClient($client = (new ClientBuilder())->build());
        $token->addScope($scope = new Scope('common'));
        $token->setIdentifier($identifier = Uuid::uuid4()->toString());
        $token->setUserIdentifier($userIdentifier = Uuid::uuid4()->toString());
        $token->setExpiryDateTime($expiryDateTime = new DateTimeImmutable());

        self::assertSame($client, $token->getClient());
        self::assertSame([$scope], $token->getScopes());
        self::assertSame($identifier, $token->getIdentifier());
        self::assertSame($userIdentifier, $token->getUserIdentifier());
        self::assertSame($expiryDateTime, $token->getExpiryDateTime());
    }
}
