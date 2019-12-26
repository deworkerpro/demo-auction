<?php

declare(strict_types=1);

use Slim\Factory\AppFactory;

http_response_code(500);

require __DIR__ . '/../vendor/autoload.php';

$builder = new DI\ContainerBuilder();
$builder->addDefinitions(require __DIR__ . '/../config/dependencies.php');
$container = $builder->build();

$app = AppFactory::createFromContainer($container);

(require __DIR__ . '/../config/middleware.php')($app, $container);
(require __DIR__ . '/../config/routes.php')($app);

$app->run();
