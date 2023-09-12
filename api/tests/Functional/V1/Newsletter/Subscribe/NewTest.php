<?php

declare(strict_types=1);

namespace Test\Functional\V1\Newsletter\Subscribe;

use Test\Functional\AuthHeader;
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

    public function testSuccess(): void
    {
        $response = $this->app()->handle(
            self::json('POST', '/v1/newsletter/subscribe')
                ->withHeader('Authorization', AuthHeader::for('00000000-0000-0000-0000-000000000001', 'user'))
        );

        self::assertEquals(201, $response->getStatusCode());
    }
}
