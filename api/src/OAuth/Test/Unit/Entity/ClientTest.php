<?php

declare(strict_types=1);

namespace App\OAuth\Test\Unit\Entity;

use App\OAuth\Entity\Client;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @internal
 */
final class ClientTest extends TestCase
{
    public function testCreate(): void
    {
        $client = new Client(
            $identifier = Uuid::uuid4()->toString(),
            $name = 'Client',
            $redirectUri = 'http://localhost/auth'
        );

        self::assertSame($identifier, $client->getIdentifier());
        self::assertSame($name, $client->getName());
        self::assertSame($redirectUri, $client->getRedirectUri());

        self::assertFalse($client->isConfidential());
    }
}
