#!/usr/bin/env php
<?php

declare(strict_types=1);

use Doctrine\Migrations\Configuration\Configuration;
use Doctrine\Migrations\Tools\Console\Helper\ConfigurationHelper;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;

require __DIR__ . '/../vendor/autoload.php';

if (getenv('SENTRY_DSN')) {
    Sentry\init(['dsn' => getenv('SENTRY_DSN')]);
}

/** @var ContainerInterface $container */
$container = require __DIR__ . '/../config/container.php';

$cli = new Application('Console');

if (getenv('SENTRY_DSN')) {
    $cli->setCatchExceptions(false);
}

/**
 * @var string[] $commands
 * @psalm-suppress MixedArrayAccess
 */
$commands = $container->get('config')['console']['commands'];

/** @var EntityManagerInterface $entityManager */
$entityManager = $container->get(EntityManagerInterface::class);
$connection = $entityManager->getConnection();

$configuration = new Configuration($connection);
$configuration->setMigrationsDirectory(__DIR__ . '/../src/Data/Migration');
$configuration->setMigrationsNamespace('App\Data\Migration');
$configuration->setMigrationsTableName('migrations');
$configuration->setAllOrNothing(true);
$configuration->setCheckDatabasePlatform(false);

$cli->getHelperSet()->set(new EntityManagerHelper($entityManager), 'em');
$cli->getHelperSet()->set(new ConfigurationHelper($connection, $configuration), 'configuration');

foreach ($commands as $name) {
    /** @var Command $command */
    $command = $container->get($name);
    $cli->add($command);
}

$cli->run();
