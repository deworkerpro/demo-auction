<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;

return [
    Swift_Mailer::class => static function (ContainerInterface $container) {
        /**
         * @psalm-suppress MixedArrayAccess
         * @psalm-var array{
         *     host:string,
         *     port:int,
         *     user:string,
         *     password:string,
         *     encryption:string,
         * } $config
         */
        $config = $container->get('config')['mailer'];

        $transport = (new Swift_SmtpTransport($config['host'], $config['port']))
            ->setUsername($config['user'])
            ->setPassword($config['password'])
            ->setEncryption($config['encryption']);

        return new Swift_Mailer($transport);
    },

    'config' => [
        'mailer' => [
            'host' => getenv('MAILER_HOST'),
            'port' => getenv('MAILER_PORT'),
            'user' => getenv('MAILER_USER'),
            'password' => getenv('MAILER_PASSWORD'),
            'encryption' => getenv('MAILER_ENCRYPTION'),
            'from' => [getenv('MAILER_FROM_EMAIL') => 'Auction'],
        ],
    ],
];
