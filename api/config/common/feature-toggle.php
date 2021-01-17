<?php

declare(strict_types=1);

use App\FeatureToggle\FeatureFlag;
use App\FeatureToggle\Features;
use Psr\Container\ContainerInterface;

return [
    FeatureFlag::class => DI\get(Features::class),

    Features::class => static function (ContainerInterface $container): Features {
        /**
         * @psalm-suppress MixedArrayAccess
         * @psalm-var array{features: array<string, bool>} $config
         */
        $config = $container->get('config')['feature-toggle'];

        return new Features($config['features']);
    },

    'config' => [
        'feature-toggle' => [
            'features' => [
                'NEW_HOME' => false,
            ],
        ],
    ],
];
