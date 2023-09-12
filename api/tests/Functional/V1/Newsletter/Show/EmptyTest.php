<?php

declare(strict_types=1);

namespace Test\Functional\V1\Newsletter\Show;

use Test\Functional\AuthHeader;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class EmptyTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([
            EmptyFixture::class,
        ]);
    }

    public function testSuccess(): void
    {
        $response = $this->app()->handle(
            self::json('GET', '/v1/newsletter')
                ->withHeader('Authorization', AuthHeader::for('00000000-0000-0000-0000-000000000001', 'user'))
        );

        self::assertEquals(200, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        self::assertEquals([
            'subscribed' => false,
        ], Json::decode($body));
    }
}
