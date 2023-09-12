<?php

declare(strict_types=1);

namespace App\Queue\Connection\AMQP;

use App\Auth;
use App\EventStore\Entity\Event;
use App\EventStore\Entity\EventRepository;
use App\EventStore\EventListenerResolver\EventListenerResolver;
use App\EventStore\EventListenerResolver\Listener;
use App\EventStore\ExchangeResolver\ExchangeResolver;
use App\EventStore\ExchangeResolver\Route;
use App\Newsletter;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

return [
    EventRepository::class => static function (ContainerInterface $container): EventRepository {
        $em = $container->get(EntityManagerInterface::class);
        $repo = $em->getRepository(Event::class);
        return new EventRepository($em, $repo);
    },

    ExchangeResolver::class => static function (ContainerInterface $container): ExchangeResolver {
        /**
         * @psalm-suppress MixedArrayAccess
         * @var array{
         *     routes: array<string, string>
         * } $config
         */
        $config = $container->get('config')['events'];

        /** @var Route[] $routes */
        $routes = [];

        foreach ($config['routes'] as $event => $exchange) {
            $routes[] = new Route(
                event: $event,
                exchange: $exchange
            );
        }

        return new ExchangeResolver($routes);
    },

    EventListenerResolver::class => static function (ContainerInterface $container): EventListenerResolver {
        /**
         * @psalm-suppress MixedArrayAccess
         * @var array{
         *     listeners: array<string, array<string, string>>
         * } $config
         */
        $config = $container->get('config')['events'];

        $containerFactory = static function (): ContainerInterface {
            /** @var ContainerInterface */
            return require __DIR__ . '/../../config/container.php';
        };

        /** @var Listener[] $listeners */
        $listeners = [];

        foreach ($config['listeners'] as $queue => $pairs) {
            foreach ($pairs as $event => $listener) {
                $listeners[] = new Listener(
                    queue: $queue,
                    event: $event,
                    listener: $listener
                );
            }
        }

        return new EventListenerResolver($containerFactory, $listeners);
    },
    'config' => [
        'events' => [
            'routes' => [
                Auth\Event\UserSignedUp::class => 'auth_events',
            ],
            'listeners' => [
                'newsletter_inbox' => [
                    Auth\Event\UserSignedUp::class => Newsletter\Listener\SubscribeToNewsletterByUserSignedUp::class,
                ],
            ],
        ],
    ],
];