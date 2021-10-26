<?php

declare(strict_types=1);

use Finesse\SwiftMailerDefaultsPlugin\SwiftMailerDefaultsPlugin;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Mailer\EventListener\EnvelopeListener;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mime\Address;
use function App\env;

return [
    Swift_Mailer::class => static function (ContainerInterface $container) {
        /**
         * @psalm-suppress MixedArrayAccess
         * @var array{
         *     host:string,
         *     port:int,
         *     user:string,
         *     password:string,
         *     encryption:string,
         *     from:array{email:string, name:string}
         * } $config
         */
        $config = $container->get('config')['mailer'];

        $transport = (new Swift_SmtpTransport($config['host'], $config['port']))
            ->setUsername($config['user'])
            ->setPassword($config['password'])
            ->setEncryption($config['encryption']);

        $mailer = new Swift_Mailer($transport);

        $mailer->registerPlugin(new SwiftMailerDefaultsPlugin([
            'from' => [$config['from']['email'] => $config['from']['name']],
        ]));

        return $mailer;
    },

    MailerInterface::class => static function (ContainerInterface $container): MailerInterface {
        /**
         * @psalm-suppress MixedArrayAccess
         * @var array{
         *     host:string,
         *     port:int,
         *     user:string,
         *     password:string,
         *     encryption:string,
         *     from:array{email:string, name:string}
         * } $config
         */
        $config = $container->get('config')['mailer'];

        $dispatcher = new EventDispatcher();

        $dispatcher->addSubscriber(new EnvelopeListener(new Address(
            $config['from']['email'],
            $config['from']['name']
        )));

        $transport = (new EsmtpTransport(
            $config['host'],
            $config['port'],
            $config['encryption'] === 'tls',
            $dispatcher,
            $container->get(LoggerInterface::class)
        ))
            ->setUsername($config['user'])
            ->setPassword($config['password']);

        return new Mailer($transport);
    },

    'config' => [
        'mailer' => [
            'host' => env('MAILER_HOST'),
            'port' => (int)env('MAILER_PORT'),
            'user' => env('MAILER_USERNAME'),
            'password' => env('MAILER_PASSWORD'),
            'encryption' => env('MAILER_ENCRYPTION'),
            'from' => [
                'email' => env('MAILER_FROM_EMAIL'),
                'name' => 'Auction',
            ],
        ],
    ],
];
