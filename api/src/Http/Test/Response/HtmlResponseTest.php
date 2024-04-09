<?php

declare(strict_types=1);

namespace App\Http\Test\Response;

use App\Http\Response\HtmlResponse;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class HtmlResponseTest extends TestCase
{
    public function testDefault(): void
    {
        $response = new HtmlResponse($html = '<html lang="en"></html>');

        self::assertSame('text/html', $response->getHeaderLine('Content-Type'));
        self::assertSame($html, $response->getBody()->getContents());
        self::assertSame(200, $response->getStatusCode());
    }

    public function testWithCode(): void
    {
        $response = new HtmlResponse($html = '<html lang="en"></html>', 201);

        self::assertSame('text/html', $response->getHeaderLine('Content-Type'));
        self::assertSame($html, $response->getBody()->getContents());
        self::assertSame(201, $response->getStatusCode());
    }
}
