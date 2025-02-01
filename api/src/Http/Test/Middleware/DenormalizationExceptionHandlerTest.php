<?php

declare(strict_types=1);

namespace App\Http\Test\Middleware;

use App\Http\Middleware\DenormalizationExceptionHandler;
use App\Validator\ValidationException;
use Exception;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;
use Symfony\Component\Serializer\Exception\ExtraAttributesException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Exception\PartialDenormalizationException;

/**
 * @internal
 */
#[CoversClass(DenormalizationExceptionHandler::class)]
final class DenormalizationExceptionHandlerTest extends TestCase
{
    public function testNormal(): void
    {
        $middleware = new DenormalizationExceptionHandler();

        $handler = self::createStub(RequestHandlerInterface::class);
        $handler->method('handle')->willReturn($source = self::createResponse());

        $response = $middleware->process(self::createRequest(), $handler);

        self::assertEquals($source, $response);
    }

    public function testBatchTypeException(): void
    {
        $middleware = new DenormalizationExceptionHandler();

        $handler = self::createStub(RequestHandlerInterface::class);
        $handler->method('handle')->willThrowException(
            new PartialDenormalizationException(
                ['name' => 42, 'age' => 'John'],
                [
                    NotNormalizableValueException::createForUnexpectedDataType('Error', 42, ['string'], 'name'),
                    NotNormalizableValueException::createForUnexpectedDataType('Error', 'John', ['int'], 'age'),
                ]
            )
        );

        try {
            $middleware->process(self::createRequest(), $handler);
            self::fail('Expected exception is not thrown');
        } catch (Exception $exception) {
            self::assertInstanceOf(ValidationException::class, $exception);

            self::assertTrue($exception->getViolations()->has(0));
            $violation = $exception->getViolations()->get(0);
            self::assertSame('The type must be one of "string" ("int" given).', $violation->getMessage());
            self::assertSame('name', $violation->getPropertyPath());

            self::assertTrue($exception->getViolations()->has(1));
            $violation = $exception->getViolations()->get(1);
            self::assertSame('The type must be one of "int" ("string" given).', $violation->getMessage());
            self::assertSame('age', $violation->getPropertyPath());
        }
    }

    public function testTypeException(): void
    {
        $middleware = new DenormalizationExceptionHandler();

        $handler = self::createStub(RequestHandlerInterface::class);
        $handler->method('handle')->willThrowException(
            NotNormalizableValueException::createForUnexpectedDataType('Error', 42, ['string'], 'name')
        );

        try {
            $middleware->process(self::createRequest(), $handler);
            self::fail('Expected exception is not thrown');
        } catch (Exception $exception) {
            self::assertInstanceOf(ValidationException::class, $exception);
            self::assertTrue($exception->getViolations()->has(0));
            $violation = $exception->getViolations()->get(0);
            self::assertSame('The type must be one of "string" ("int" given).', $violation->getMessage());
            self::assertSame('name', $violation->getPropertyPath());
        }
    }

    public function testExtraAttributesException(): void
    {
        $middleware = new DenormalizationExceptionHandler();

        $handler = self::createStub(RequestHandlerInterface::class);
        $handler->method('handle')->willThrowException(
            new ExtraAttributesException(['age'])
        );

        try {
            $middleware->process(self::createRequest(), $handler);
            self::fail('Expected exception is not thrown');
        } catch (Exception $exception) {
            self::assertInstanceOf(ValidationException::class, $exception);
            self::assertTrue($exception->getViolations()->has(0));
            $violation = $exception->getViolations()->get(0);
            self::assertSame('The attribute is not allowed.', $violation->getMessage());
            self::assertSame('age', $violation->getPropertyPath());
        }
    }

    private static function createRequest(): ServerRequestInterface
    {
        return (new ServerRequestFactory())->createServerRequest('POST', 'http://test');
    }

    private static function createResponse(): ResponseInterface
    {
        return (new ResponseFactory())->createResponse();
    }
}
