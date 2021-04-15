<?php

declare(strict_types=1);

use Finesse\SwiftMailerDefaultsPlugin\SwiftMailerDefaultsPlugin;
use Psr\Container\ContainerInterface;
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
         *     from:array
         * } $config
         */
        $config = $container->get('config')['mailer'];

        $transport = (new Swift_SmtpTransport($config['host'], $config['port']))
            ->setUsername($config['user'])
            ->setPassword($config['password'])
            ->setEncryption($config['encryption']);

        $mailer = new Swift_Mailer($transport);

        $mailer->registerPlugin(new SwiftMailerDefaultsPlugin([
            'from' => $config['from'],
        ]));

        return $mailer;
    },

    'config' => [
        'mailer' => [
            'host' => env('MAILER_HOST'),
            'port' => env('MAILER_PORT'),
            'user' => env('MAILER_USER'),
            'password' => env('MAILER_PASSWORD'),
            'encryption' => env('MAILER_ENCRYPTION'),
            'from' => [env('MAILER_FROM_EMAIL') => 'Auction'],
        ],
    ],
];
