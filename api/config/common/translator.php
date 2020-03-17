<?php

use Psr\Container\ContainerInterface;
use Symfony\Component\Translation\Loader\PhpFileLoader;
use Symfony\Component\Translation\Loader\XliffFileLoader;
use Symfony\Component\Translation\Translator;
use Symfony\Contracts\Translation\TranslatorInterface;

return [
    TranslatorInterface::class => function (ContainerInterface $container): TranslatorInterface {
        /**
         * @psalm-suppress MixedArrayAccess
         * @psalm-var array{lang:string,resources:array<string[]>} $config
         */
        $config = $container->get('config')['translator'];

        return new Translator($config['lang']);
    },

    'config' => [
        'translator' => [
            'lang' => 'en',
        ],
    ],
];
