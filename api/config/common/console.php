<?php

declare(strict_types=1);

use App\Console;
use Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand;

return [
    'config' => [
        'console' => [
            'commands' => [
                Console\HelloCommand::class,
                ValidateSchemaCommand::class,
            ],
        ],
    ],
];
