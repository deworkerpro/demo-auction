<?php

declare(strict_types=1);

namespace App\Http\Test\Response;

use App\Http\Response\RedirectResponse;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class RedirectResponseTest extends TestCase
{
    public function testDefault(): void
    {
        $response = new RedirectResponse($location = '/location');

        self::assertSame(302, $response->getStatusCode());
        self::assertSame($location, $response->getHeaderLine('Location'));
    }

    public function testWithCode(): void
    {
        $response = new RedirectResponse('/location', 301);

        self::assertSame(301, $response->getStatusCode());
    }
}
