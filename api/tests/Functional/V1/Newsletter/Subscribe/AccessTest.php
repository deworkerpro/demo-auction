<?php

declare(strict_types=1);

namespace Test\Functional\V1\Newsletter\Subscribe;

use Test\Functional\AuthHeader;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class AccessTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([]);
    }

    public function testGuest(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/newsletter/subscribe'));

        self::assertEquals(401, $response->getStatusCode());
    }

    public function testMethod(): void
    {
        $response = $this->app()->handle(
            self::json('GET', '/v1/newsletter/subscribe')
                ->withHeader('Authorization', AuthHeader::for('00000000-0000-0000-0000-000000000001', 'user'))
        );

        self::assertEquals(405, $response->getStatusCode());
    }
}
