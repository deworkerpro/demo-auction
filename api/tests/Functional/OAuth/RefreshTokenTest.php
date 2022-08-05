<?php

declare(strict_types=1);

namespace Test\Functional\OAuth;

use App\OAuth\Entity\Scope;
use DateTimeImmutable;
use Defuse\Crypto\Crypto;
use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

use function App\env;

/**
 * @internal
 */
final class RefreshTokenTest extends WebTestCase
{
    use ArraySubsetAsserts;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([
            'code' => RefreshTokenFixture::class,
        ]);
    }

    public function testSuccess(): void
    {
        $payload = [
            'client_id' => 'frontend',
            'refresh_token_id' => 'aef50200f204dedbb244ce4539b9e',
            'access_token_id' => '50200f204dedbb244ce453',
            'scopes' => [new Scope('common')],
            'user_id' => '00000000-0000-0000-0000-000000000001',
            'expire_time' => (new DateTimeImmutable('2300-12-31 21:00:10'))->getTimestamp(),
        ];

        $token = Crypto::encryptWithPassword(Json::encode($payload), env('JWT_ENCRYPTION_KEY'));

        $response = $this->app()->handle(self::html('POST', '/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $token,
            'redirect_uri' => 'http://localhost/oauth',
            'client_id' => 'frontend',
            'access_type' => 'offline',
        ]));

        self::assertEquals(200, $response->getStatusCode());

        self::assertJson($content = (string)$response->getBody());

        $data = Json::decode($content);

        self::assertArraySubset([
            'token_type' => 'Bearer',
        ], $data);

        self::assertArrayHasKey('expires_in', $data);
        self::assertNotEmpty($data['expires_in']);

        self::assertArrayHasKey('access_token', $data);
        self::assertNotEmpty($data['access_token']);

        self::assertArrayHasKey('refresh_token', $data);
        self::assertNotEmpty($data['refresh_token']);
    }
}
