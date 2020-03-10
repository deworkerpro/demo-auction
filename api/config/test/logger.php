<?php

declare(strict_types=1);

return [
    'config' => [
        'logger' => [
            'file' => __DIR__ . '/../../var/log/' . PHP_SAPI . '/test.log',
            'stderr' => false,
        ],
    ],
];
