<?php

declare(strict_types=1);

namespace Test\Functional\V1\Auth\Join;

use Ramsey\Uuid\Uuid;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

class ConfirmTest extends WebTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([
            ConfirmFixture::class,
        ]);
    }

    public function testMethod(): void
    {
        $response = $this->app()->handle(self::json('GET', '/v1/auth/join/confirm'));

        self::assertEquals(405, $response->getStatusCode());
    }

    public function testSuccess(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/join/confirm', [
            'token' => ConfirmFixture::VALID,
        ]));

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('', (string)$response->getBody());
    }

    public function testExpired(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/join/confirm', [
            'token' => ConfirmFixture::EXPIRED,
        ]));

        self::assertEquals(409, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        self::assertEquals([
            'message' => 'Token is expired.',
        ], Json::decode($body));
    }

    public function testEmpty(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/join/confirm', []));

        self::assertEquals(422, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        self::assertEquals([
            'errors' => [
                'token' => 'This value should not be blank.',
            ],
        ], Json::decode($body));
    }

    public function testNotExisting(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/join/confirm', [
            'token' => Uuid::uuid4()->toString(),
        ]));

        self::assertEquals(409, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        self::assertEquals([
            'message' => 'Incorrect token.',
        ], Json::decode($body));
    }
}
