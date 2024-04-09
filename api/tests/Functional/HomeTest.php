<?php

declare(strict_types=1);

namespace Test\Functional;

/**
 * @internal
 */
final class HomeTest extends WebTestCase
{
    public function testMethod(): void
    {
        $response = $this->app()->handle(self::json('POST', '/'));

        self::assertSame(405, $response->getStatusCode());
    }

    public function testSuccess(): void
    {
        $response = $this->app()->handle(
            self::json('GET', '/')->withHeader('X-Features', '!NEW_HOME')
        );

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('application/json', $response->getHeaderLine('Content-Type'));
        self::assertSame('{}', (string)$response->getBody());
    }

    public function testNewHome(): void
    {
        $response = $this->app()->handle(
            self::json('GET', '/')->withHeader('X-Features', 'NEW_HOME')
        );

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('application/json', $response->getHeaderLine('Content-Type'));
        self::assertSame('{"name":"API"}', (string)$response->getBody());
    }
}
