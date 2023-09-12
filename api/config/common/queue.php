<?php

declare(strict_types=1);

namespace App\Queue\Connection\AMQP;

use App\Queue\AmqpQueue;
use App\Queue\Consumer;
use App\Queue\Publisher;
use DI;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
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

        foreach ($config['exchanges'] as $exchange => $queues) {
            $channel->exchange_declare(
                exchange: $exchange,
                type: AMQPExchangeType::FANOUT,
                durable: true,
                auto_delete: false
            );

            foreach ($queues as $queue) {
                $channel->queue_declare(
                    queue: $queue,
                    durable: true,
                    auto_delete: false
                );
                $channel->queue_bind($queue, $exchange);
            }
        }

        register_shutdown_function(static function (AMQPChannel $channel, AMQPStreamConnection $connection): void {
            $channel->close();
            $connection->close();
        }, $channel, $connection);

        return $connection;
    },

    Publisher::class => DI\get(AmqpQueue::class),
    Consumer::class => DI\get(AmqpQueue::class),

    'config' => [
        'queue' => [
            'exchanges' => [
                'auth_events' => ['newsletter_inbox'],
            ],
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
