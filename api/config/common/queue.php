<?php

declare(strict_types=1);

namespace App\Queue\Connection\AMQP;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Psr\Container\ContainerInterface;

use function App\env;

return [
    AMQPStreamConnection::class => static function (ContainerInterface $container): AMQPStreamConnection {
        /**
         * @psalm-suppress MixedArrayAccess
         * @var array{
         *     exchanges: array<string, string[]>,
         *     amqp: array{
         *         host:string,
         *         port:int,
         *         username:string,
         *         password:string,
         *         vhost:string
         *     }
         * } $config
         */
        $config = $container->get('config')['queue'];

        $connection = new AMQPStreamConnection(
            host: $config['amqp']['host'],
            port: $config['amqp']['port'],
            user: $config['amqp']['username'],
            password: $config['amqp']['password'],
            vhost: $config['amqp']['vhost']
        );

        $channel = $connection->channel();

        register_shutdown_function(static function (AMQPChannel $channel, AMQPStreamConnection $connection): void {
            $channel->close();
            $connection->close();
        }, $channel, $connection);

        return $connection;
    },

    'config' => [
        'queue' => [
            'amqp' => [
                'host' => env('AMQP_HOST'),
                'port' => 5672,
                'username' => env('AMQP_USERNAME'),
                'password' => env('AMQP_PASSWORD'),
                'vhost' => '/',
            ],
        ],
    ],
];
