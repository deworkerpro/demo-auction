<?php

declare(strict_types=1);

use App\Frontend\FrontendUrlGenerator;
use Psr\Container\ContainerInterface;

return [
    FrontendUrlGenerator::class => function (ContainerInterface $container): FrontendUrlGenerator {
        /**
         * @psalm-suppress MixedArrayAccess
         * @psalm-var array{url:string} $config
         */
        $config = $container->get('config')['frontend'];

        return new FrontendUrlGenerator($config['url']);
    },

    'config' => [
        'frontend' => [
            'url' => getenv('FRONTEND_URL'),
        ],
    ],
];
