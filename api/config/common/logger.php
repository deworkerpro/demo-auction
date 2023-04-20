<?php

declare(strict_types=1);

use App\FeatureToggle\FeaturesMonologProcessor;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Processor\ProcessorInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

use function App\env;

return [
    LoggerInterface::class => static function (ContainerInterface $container) {
        /**
         * @psalm-suppress MixedArrayAccess
         * @var array{
         *     debug:bool,
         *     stderr:bool,
         *     file:string,
         *     processors:string[]
         * } $config
         */
        $config = $container->get('config')['logger'];

        $level = $config['debug'] ? Level::Debug : Level::Info;

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
            'debug' => (bool)env('APP_DEBUG', '0'),
            'file' => null,
            'stderr' => true,
            'processors' => [
                FeaturesMonologProcessor::class,
            ],
        ],
    ],
];
