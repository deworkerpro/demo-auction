<?php

declare(strict_types=1);

use App\FeatureToggle\FeaturesMonologProcessor;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\ProcessorInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return [
    LoggerInterface::class => function (ContainerInterface $container) {
        /**
         * @psalm-suppress MixedArrayAccess
         * @psalm-var array{
         *     debug:bool,
         *     stderr:bool,
         *     file:string,
         *     processors:string[]
         * } $config
         */
        $config = $container->get('config')['logger'];

        $level = $config['debug'] ? Logger::DEBUG : Logger::INFO;

        $log = new Logger('API');

        if ($config['stderr']) {
            $log->pushHandler(new StreamHandler('php://stderr', $level));
        }

        if (!empty($config['file'])) {
            $log->pushHandler(new StreamHandler($config['file'], $level));
        }

        foreach ($config['processors'] as $class) {
            /** @var ProcessorInterface $processor */
            $processor = $container->get($class);
            $log->pushProcessor($processor);
        }

        return $log;
    },

    'config' => [
        'logger' => [
            'debug' => (bool)getenv('APP_DEBUG'),
            'file' => null,
            'stderr' => true,
            'processors' => [
                FeaturesMonologProcessor::class
            ],
        ],
    ],
];
