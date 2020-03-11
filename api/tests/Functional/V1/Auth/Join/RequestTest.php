<?php

declare(strict_types=1);

namespace Test\Functional\V1\Auth\Join;

use Test\Functional\Json;
use Test\Functional\WebTestCase;

class RequestTest extends WebTestCase
{
    public function testMethod(): void
    {
        $response = $this->app()->handle(self::json('GET', '/v1/auth/join'));

        self::assertEquals(405, $response->getStatusCode());
    }

    public function testSuccess(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/join', [
            'email' => 'new-user@app.test',
            'password' => 'new-password',
        ]));

        self::assertEquals(201, $response->getStatusCode());
        self::assertEquals('{}', (string)$response->getBody());
    }

    public function testExisting(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/join', [
            'email' => 'user@app.test',
            'password' => 'new-password',
        ]));

        self::assertEquals(409, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        self::assertEquals([
            'message' => 'User already exists.',
        ], Json::decode($body));
    }

    public function testEmpty(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/join', []));

        self::assertEquals(500, $response->getStatusCode());
    }

    public function testNotValid(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/join', [
            'email' => 'not-email',
            'password' => '',
        ]));

        self::assertEquals(500, $response->getStatusCode());
    }
}
