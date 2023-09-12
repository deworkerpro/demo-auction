<?php

declare(strict_types=1);

namespace Test\Functional\V1\Newsletter\Unsubscribe;

use Test\Functional\AuthHeader;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class NewTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([
            NewFixture::class,
        ]);
    }

    public function testError(): void
    {
        $response = $this->app()->handle(
            self::json('POST', '/v1/newsletter/unsubscribe')
                ->withHeader('Authorization', AuthHeader::for('00000000-0000-0000-0000-000000000001', 'user'))
        );

        self::assertEquals(409, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        self::assertEquals([
            'message' => 'Subscription is not found.',
        ], Json::decode($body));
    }
}
