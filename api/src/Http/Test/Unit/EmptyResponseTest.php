<?php

declare(strict_types=1);

namespace App\Http\Test\Unit;

use App\Http\EmptyResponse;
use PHPUnit\Framework\TestCase;

class EmptyResponseTest extends TestCase
{
    public function testDefault(): void
    {
        $response = new EmptyResponse();

        self::assertEquals(204, $response->getStatusCode());
        self::assertFalse($response->hasHeader('Content-Type'));

        self::assertEquals('', (string)$response->getBody());
        self::assertFalse($response->getBody()->isWritable());
    }

    public function testWithCode(): void
    {
        $response = new EmptyResponse(201);

        self::assertEquals(201, $response->getStatusCode());
    }
}
