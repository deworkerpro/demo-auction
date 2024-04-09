<?php

declare(strict_types=1);

namespace App\Http\Test\Response;

use App\Http\Response\JsonResponse;
use JsonException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @internal
 */
final class JsonResponseTest extends TestCase
{
    public function testWithCode(): void
    {
        $response = new JsonResponse(0, 201);

        self::assertSame('application/json', $response->getHeaderLine('Content-Type'));
        self::assertSame('0', $response->getBody()->getContents());
        self::assertSame(201, $response->getStatusCode());
    }

    /**
     * @throws JsonException
     */
    #[DataProvider('provideResponseCases')]
    public function testResponse(mixed $source, mixed $expect): void
    {
        $response = new JsonResponse($source);

        self::assertSame('application/json', $response->getHeaderLine('Content-Type'));
        self::assertEquals($expect, $response->getBody()->getContents());
        self::assertSame(200, $response->getStatusCode());
    }

    /**
     * @return iterable<array-key, array<array-key, mixed>>
     */
    public static function provideResponseCases(): iterable
    {
        $object = new stdClass();
        $object->str = 'value';
        $object->int = 1;
        $object->none = null;

        $array = [
            'str' => 'value',
            'int' => 1,
            'none' => null,
        ];

        return [
            'null' => [null, 'null'],
            'empty' => ['', '""'],
            'number' => [12, '12'],
            'string' => ['12', '"12"'],
            'object' => [$object, '{"str":"value","int":1,"none":null}'],
            'array' => [$array, '{"str":"value","int":1,"none":null}'],
        ];
    }
}
