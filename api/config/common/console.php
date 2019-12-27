<?php

declare(strict_types=1);

use App\Console;

return [
    'config' => [
        'console' => [
            'commands' => [
                Console\HelloCommand::class,
            ],
        ],
    ],
];
