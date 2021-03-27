<?php

declare(strict_types=1);

use App\Console\FixturesLoadCommand;
use Doctrine\Migrations;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\Command\SchemaTool;
use Psr\Container\ContainerInterface;

return [
    FixturesLoadCommand::class => static function (ContainerInterface $container) {
        /**
         * @psalm-suppress MixedArrayAccess
         * @var array{fixture_paths:string[]} $config
         */
        $config = $container->get('config')['console'];

        return new FixturesLoadCommand(
            $container->get(EntityManagerInterface::class),
            $config['fixture_paths'],
        );
    },

    'config' => [
        'console' => [
            'commands' => [
                FixturesLoadCommand::class,

                SchemaTool\DropCommand::class,

                Migrations\Tools\Console\Command\DiffCommand::class,
                Migrations\Tools\Console\Command\GenerateCommand::class,
            ],
            'fixture_paths' => [
                __DIR__ . '/../../src/Auth/Fixture',
            ],
        ],
    ],
];
