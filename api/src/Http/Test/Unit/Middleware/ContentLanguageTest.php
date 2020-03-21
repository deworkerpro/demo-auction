<?php

declare(strict_types=1);

namespace App\Http\Test\Unit\Middleware;

use Middlewares\ContentLanguage;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

/**
 * @covers ContentLanguage
 */
class ContentLanguageTest extends TestCase
{
    public function testDefault(): void
    {
        $middleware = new ContentLanguage(['en', 'ru']);

        $handler = $this->createStub(RequestHandlerInterface::class);
        $handler->method('handle')->willReturnCallback(
            static function (ServerRequestInterface $request): ResponseInterface {
                self::assertEquals('en', $request->getHeaderLine('Accept-Language'));
                return (new ResponseFactory())->createResponse();
            }
        );

        $middleware->process(self::createRequest(), $handler);
    }

    public function testAccepted(): void
    {
        $middleware = new ContentLanguage(['en', 'ru']);

        $handler = $this->createStub(RequestHandlerInterface::class);
        $handler->method('handle')->willReturnCallback(
            static function (ServerRequestInterface $request): ResponseInterface {
                self::assertEquals('ru', $request->getHeaderLine('Accept-Language'));
                return (new ResponseFactory())->createResponse();
            }
        );

        $request = self::createRequest()->withHeader('Accept-Language', 'ru');

        $middleware->process($request, $handler);
    }

    public function testMulti(): void
    {
        $middleware = new ContentLanguage(['en', 'ru']);

        $handler = $this->createStub(RequestHandlerInterface::class);
        $handler->method('handle')->willReturnCallback(
            static function (ServerRequestInterface $request): ResponseInterface {
                self::assertEquals('ru', $request->getHeaderLine('Accept-Language'));
                return (new ResponseFactory())->createResponse();
            }
        );

        $request = self::createRequest()->withHeader('Accept-Language', 'es;q=0.9, ru;q=0.8, *;q=0.5');

        $middleware->process($request, $handler);
    }

    public function testOther(): void
    {
        $middleware = new ContentLanguage(['en', 'ru']);

        $handler = $this->createStub(RequestHandlerInterface::class);
        $handler->method('handle')->willReturnCallback(
            static function (ServerRequestInterface $request): ResponseInterface {
                self::assertEquals('en', $request->getHeaderLine('Accept-Language'));
                return (new ResponseFactory())->createResponse();
            }
        );

        $request = self::createRequest()->withHeader('Accept-Language', 'es');

        $middleware->process($request, $handler);
    }

    private static function createRequest(): ServerRequestInterface
    {
        return (new ServerRequestFactory())->createServerRequest('POST', 'http://test');
    }
}
