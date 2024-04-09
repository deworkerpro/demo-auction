<?php

declare(strict_types=1);

namespace Test\Functional\V1\Auth\Join;

use Override;
use Ramsey\Uuid\Uuid;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class ConfirmTest extends WebTestCase
{
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([
            ConfirmFixture::class,
        ]);
    }

    public function testMethod(): void
    {
        $response = $this->app()->handle(self::json('GET', '/v1/auth/join/confirm'));

        self::assertSame(405, $response->getStatusCode());
    }

    public function testSuccess(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/join/confirm', [
            'token' => ConfirmFixture::VALID,
        ]));

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('', (string)$response->getBody());
    }

    public function testExpired(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/join/confirm', [
            'token' => ConfirmFixture::EXPIRED,
        ]));

        self::assertSame(409, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        self::assertSame([
            'message' => 'Token is expired.',
        ], Json::decode($body));
    }

    public function testEmpty(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/join/confirm', []));

        self::assertSame(422, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        self::assertSame([
            'errors' => [
                'token' => 'The type must be one of "string" ("null" given).',
            ],
        ], Json::decode($body));
    }

    public function testNotExisting(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/join/confirm', [
            'token' => Uuid::uuid4()->toString(),
        ]));

        self::assertSame(409, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        self::assertSame([
            'message' => 'Incorrect token.',
        ], Json::decode($body));
    }
}
