<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Handlers\ErrorHandler;
use Slim\Interfaces\CallableResolverInterface;
use Slim\Middleware\ErrorMiddleware;

return [
    ErrorMiddleware::class => static function (ContainerInterface $container): ErrorMiddleware {
        /** @var CallableResolverInterface $callableResolver */
        $callableResolver = $container->get(CallableResolverInterface::class);
        /** @var ResponseFactoryInterface $responseFactory */
        $responseFactory = $container->get(ResponseFactoryInterface::class);
        /**
         * @psalm-suppress MixedArrayAccess
         * @psalm-var array{display_details:bool,log:bool} $config
         */
        $config = $container->get('config')['errors'];

        $middleware = new ErrorMiddleware(
            $callableResolver,
            $responseFactory,
            $config['display_details'],
            $config['log'],
            true
        );

        $middleware->setDefaultErrorHandler(
            new ErrorHandler($callableResolver, $responseFactory)
        );

        return $middleware;
    },

    'config' => [
        'errors' => [
            'display_details' => (bool)getenv('APP_DEBUG'),
            'log' => true,
        ],
    ],
];
