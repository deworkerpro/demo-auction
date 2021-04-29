<?php

declare(strict_types=1);

namespace App\OAuth\Test\Unit\Entity;

use App\OAuth\Entity\RefreshToken;
use App\OAuth\Test\Builder\AccessTokenBuilder;
use App\OAuth\Test\Builder\ClientBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @internal
 */
final class RefreshTokenTest extends TestCase
{
    public function testCreate(): void
    {
        $token = new RefreshToken();

        $accessToken = (new AccessTokenBuilder())
            ->withUserIdentifier($userIdentifier = Uuid::uuid4()->toString())
            ->build((new ClientBuilder())->build());

        $token->setIdentifier($identifier = Uuid::uuid4()->toString());
        $token->setExpiryDateTime($expiryDateTime = new DateTimeImmutable());
        $token->setAccessToken($accessToken);

        self::assertSame($accessToken, $token->getAccessToken());
        self::assertSame($identifier, $token->getIdentifier());
        self::assertSame($userIdentifier, $token->getUserIdentifier());
        self::assertSame($expiryDateTime, $token->getExpiryDateTime());
    }
}
