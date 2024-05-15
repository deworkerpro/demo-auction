<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

return [
    ServerRequestFactoryInterface::class => static fn (): ServerRequestFactoryInterface => new ServerRequestFactory(),
    ResponseFactoryInterface::class => static fn (): ResponseFactoryInterface => new ResponseFactory(),
];
