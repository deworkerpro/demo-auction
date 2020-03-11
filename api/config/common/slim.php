<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Slim\CallableResolver;
use Slim\Interfaces\CallableResolverInterface;

return [
    CallableResolverInterface::class => static function (ContainerInterface $container): CallableResolverInterface {
        return new CallableResolver($container);
    },
];
