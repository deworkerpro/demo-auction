<?php

declare(strict_types=1);

namespace Test\Functional\OAuth;

use DateTimeImmutable;
use Defuse\Crypto\Crypto;
use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

use function App\env;

/**
 * @internal
 */
final class AuthorizationCodeTest extends WebTestCase
{
    use ArraySubsetAsserts;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([
            'code' => AuthorizationCodeFixture::class,
        ]);
    }

    public function testMethod(): void
    {
        $response = $this->app()->handle(self::json('GET', '/token'));
        self::assertEquals(405, $response->getStatusCode());
    }

    public function testSuccess(): void
    {
        $verifier = PKCE::verifier();
        $challenge = PKCE::challenge($verifier);

        $payload = [
            'client_id' => 'frontend',
            'redirect_uri' => 'http://localhost/oauth',
            'auth_code_id' => 'def50200f204dedbb244ce4539b9e',
            'scopes' => 'common',
            'user_id' => '00000000-0000-0000-0000-000000000001',
            'expire_time' => (new DateTimeImmutable('2300-12-31 21:00:10'))->getTimestamp(),
            'code_challenge' => $challenge,
            'code_challenge_method' => 'S256',
        ];

        $code = Crypto::encryptWithPassword(Json::encode($payload), env('JWT_ENCRYPTION_KEY'));

        $response = $this->app()->handle(self::html('POST', '/token', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => 'http://localhost/oauth',
            'client_id' => 'frontend',
            'code_verifier' => $verifier,
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

    public function testInvalidVerifier(): void
    {
        $challenge = PKCE::challenge(PKCE::verifier());

        $payload = [
            'client_id' => 'frontend',
            'redirect_uri' => 'http://localhost/oauth',
            'auth_code_id' => 'def50200f204dedbb244ce4539b9e',
            'scopes' => 'common',
            'user_id' => '00000000-0000-0000-0000-000000000001',
            'expire_time' => (new DateTimeImmutable('2300-12-31 21:00:10'))->getTimestamp(),
            'code_challenge' => $challenge,
            'code_challenge_method' => 'S256',
        ];

        $code = Crypto::encryptWithPassword(Json::encode($payload), env('JWT_ENCRYPTION_KEY'));

        $response = $this->app()->handle(self::html('POST', '/token', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => 'http://localhost/oauth',
            'client_id' => 'frontend',
            'code_verifier' => PKCE::verifier(),
            'access_type' => 'offline',
        ]));

        self::assertEquals(400, $response->getStatusCode());
    }

    public function testWithoutVerifier(): void
    {
        $verifier = PKCE::verifier();
        $challenge = PKCE::challenge($verifier);

        $payload = [
            'client_id' => 'frontend',
            'redirect_uri' => 'http://localhost/oauth',
            'auth_code_id' => 'def50200f204dedbb244ce4539b9e',
            'scopes' => 'common',
            'user_id' => '00000000-0000-0000-0000-000000000001',
            'expire_time' => (new DateTimeImmutable('2300-12-31 21:00:10'))->getTimestamp(),
            'code_challenge' => $challenge,
            'code_challenge_method' => 'S256',
        ];

        $code = Crypto::encryptWithPassword(Json::encode($payload), env('JWT_ENCRYPTION_KEY'));

        $response = $this->app()->handle(self::html('POST', '/token', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => 'http://localhost/oauth',
            'client_id' => 'invalid',
            'access_type' => 'offline',
        ]));

        self::assertEquals(401, $response->getStatusCode());
    }

    public function testInvalidClient(): void
    {
        $verifier = PKCE::verifier();
        $challenge = PKCE::challenge($verifier);

        $payload = [
            'client_id' => 'invalid',
            'redirect_uri' => 'http://localhost/oauth',
            'auth_code_id' => 'def50200f204dedbb244ce4539b9e',
            'scopes' => 'common',
            'user_id' => '00000000-0000-0000-0000-000000000001',
            'expire_time' => (new DateTimeImmutable('2300-12-31 21:00:10'))->getTimestamp(),
            'code_challenge' => $challenge,
            'code_challenge_method' => 'S256',
        ];

        $code = Crypto::encryptWithPassword(Json::encode($payload), env('JWT_ENCRYPTION_KEY'));

        $response = $this->app()->handle(self::html('POST', '/token', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => 'http://localhost/oauth',
            'client_id' => 'invalid',
            'code_verifier' => $verifier,
            'access_type' => 'offline',
        ]));

        self::assertEquals(401, $response->getStatusCode());
    }
}
