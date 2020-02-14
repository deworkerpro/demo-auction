<?php

declare(strict_types=1);

use Doctrine\Migrations;
use Doctrine\ORM\Tools\Console\Command\SchemaTool;

return [
    'config' => [
        'console' => [
            'commands' => [
                SchemaTool\DropCommand::class,

                Migrations\Tools\Console\Command\DiffCommand::class,
                Migrations\Tools\Console\Command\GenerateCommand::class,
            ],
        ],
    ],
];
