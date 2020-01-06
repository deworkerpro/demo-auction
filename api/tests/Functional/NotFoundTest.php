<?php

declare(strict_types=1);

namespace Test\Functional;

class NotFoundTest extends WebTestCase
{
    public function testNotFound(): void
    {
        $response = $this->app()->handle(self::json('GET', '/not-found'));

        self::assertEquals(404, $response->getStatusCode());
    }
}
