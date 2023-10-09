<?php

declare(strict_types=1);

use App\Csrf\CsrfStorage;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Csrf\Guard;

return [
    Guard::class => static function (ContainerInterface $container): Guard {
        $storage = new CsrfStorage($container->get(Redis::class));

        return new Guard(
            responseFactory: $container->get(ResponseFactoryInterface::class),
            storage: $storage,
            failureHandler: static fn (ServerRequestInterface $request, RequestHandlerInterface $handler) => $handler->handle($request->withAttribute('csrf_status', false)),
            persistentTokenMode: false
        );
    },
];
