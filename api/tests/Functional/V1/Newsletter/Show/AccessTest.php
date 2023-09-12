<?php

declare(strict_types=1);

namespace Test\Functional\V1\Newsletter\Show;

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
        $response = $this->app()->handle(self::json('GET', '/v1/newsletter'));

        self::assertEquals(401, $response->getStatusCode());
    }
}
