<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;

use function App\env;

return [
    Redis::class => static function (ContainerInterface $container) {
        /**
         * @psalm-suppress MixedArrayAccess
         * @var array{
         *     host:string,
         *     port:int,
         *     password:string,
         * } $config
         */
        $config = $container->get('config')['redis'];

        return new Redis([
            'host' => $config['host'],
            'port' => $config['port'],
            'connectTimeout' => 1,
            'auth' => $config['password'],
            'backoff' => [
                'algorithm' => Redis::BACKOFF_ALGORITHM_DECORRELATED_JITTER,
                'base' => 500,
                'cap' => 750,
            ],
        ]);
    },

    'config' => [
        'redis' => [
            'host' => env('REDIS_HOST'),
            'port' => 6379,
            'password' => env('REDIS_PASSWORD'),
        ],
    ],
];
