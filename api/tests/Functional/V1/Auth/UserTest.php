<?php

declare(strict_types=1);

namespace Test\Functional\V1\Auth;

use Override;
use Test\Functional\AuthHeader;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class UserTest extends WebTestCase
{
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([
            UserFixture::class,
        ]);
    }

    public function testGuest(): void
    {
        $response = $this->app()->handle(self::json('GET', '/v1/auth/user'));

        self::assertSame(401, $response->getStatusCode());
    }

    public function testUser(): void
    {
        $response = $this->app()->handle(
            self::json('GET', '/v1/auth/user')
                ->withHeader('Authorization', AuthHeader::for('00000000-0000-0000-0000-000000000001', 'user'))
        );

        self::assertSame(200, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        self::assertSame([
            'id' => '00000000-0000-0000-0000-000000000001',
            'role' => 'user',
        ], Json::decode($body));
    }

    public function testAdmin(): void
    {
        $response = $this->app()->handle(
            self::json('GET', '/v1/auth/user')
                ->withHeader('Authorization', AuthHeader::for('00000000-0000-0000-0000-000000000001', 'admin'))
        );

        self::assertSame(200, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        self::assertSame([
            'id' => '00000000-0000-0000-0000-000000000001',
            'role' => 'admin',
        ], Json::decode($body));
    }
}
