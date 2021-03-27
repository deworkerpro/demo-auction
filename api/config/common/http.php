<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Psr7\Factory\ResponseFactory;

return [
    ResponseFactoryInterface::class => static fn (): ResponseFactoryInterface => new ResponseFactory(),
];
