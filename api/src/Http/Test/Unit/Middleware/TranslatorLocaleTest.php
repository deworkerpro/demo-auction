<?php

declare(strict_types=1);

namespace App\Http\Test\Unit\Middleware;

use App\Http\Middleware\TranslatorLocale;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;
use Symfony\Component\Translation\Translator;

/**
 * @covers TranslatorLocale
 */
class TranslatorLocaleTest extends TestCase
{
    public function testDefault(): void
    {
        $translator = $this->createMock(Translator::class);
        $translator->expects($this->once())->method('setLocale')->with(
            $this->equalTo('en')
        );

        $middleware = new TranslatorLocale($translator, ['en', 'ru']);

        $handler = $this->createStub(RequestHandlerInterface::class);
        $handler->method('handle')->willReturn($source = self::createResponse());

        $response = $middleware->process(self::createRequest(), $handler);

        self::assertEquals($source, $response);
    }

    public function testAccepted(): void
    {
        $translator = $this->createMock(Translator::class);
        $translator->expects($this->once())->method('setLocale')->with(
            $this->equalTo('ru')
        );

        $middleware = new TranslatorLocale($translator, ['en', 'ru']);

        $handler = $this->createStub(RequestHandlerInterface::class);
        $handler->method('handle')->willReturn(self::createResponse());

        $request = self::createRequest()->withHeader('Accept-Language', 'ru');

        $middleware->process($request, $handler);
    }

    public function testMulti(): void
    {
        $translator = $this->createMock(Translator::class);
        $translator->expects($this->once())->method('setLocale')->with(
            $this->equalTo('ru')
        );

        $middleware = new TranslatorLocale($translator, ['en', 'fr', 'ru']);

        $handler = $this->createStub(RequestHandlerInterface::class);
        $handler->method('handle')->willReturn(self::createResponse());

        $request = self::createRequest()->withHeader('Accept-Language', 'es;q=0.9, ru;q=0.8, *;q=0.5');

        $middleware->process($request, $handler);
    }

    public function testOther(): void
    {
        $translator = $this->createMock(Translator::class);
        $translator->expects($this->once())->method('setLocale')->with(
            $this->equalTo('en')
        );

        $middleware = new TranslatorLocale($translator, ['en', 'ru']);

        $handler = $this->createStub(RequestHandlerInterface::class);
        $handler->method('handle')->willReturn(self::createResponse());

        $request = self::createRequest()->withHeader('Accept-Language', 'es');

        $middleware->process($request, $handler);
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
