<?php

declare(strict_types=1);

namespace Test\Functional;

use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;

class NotFoundTest extends WebTestCase
{
    use ArraySubsetAsserts;

    public function testNotFound(): void
    {
        $response = $this->app()->handle(self::json('GET', '/not-found'));

        self::assertEquals(404, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        $data = Json::decode($body);

        self::assertArraySubset([
            'message' => '404 Not Found',
        ], $data);
    }
}
