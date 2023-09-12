<?php

declare(strict_types=1);

namespace Test\Functional\V1\Newsletter\Unsubscribe;

use Test\Functional\AuthHeader;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class ExistingTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([
            ExistingFixture::class,
        ]);
    }

    public function testSuccess(): void
    {
        $response = $this->app()->handle(
            self::json('POST', '/v1/newsletter/unsubscribe')
                ->withHeader('Authorization', AuthHeader::for('00000000-0000-0000-0000-000000000001', 'user'))
        );

        self::assertEquals(204, $response->getStatusCode());
    }
}
