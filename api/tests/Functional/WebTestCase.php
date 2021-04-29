<?php

declare(strict_types=1);

namespace Test\Functional;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Psr7\Factory\ServerRequestFactory;

/**
 * @internal
 */
abstract class WebTestCase extends TestCase
{
    private ?App $app = null;
    private ?MailerClient $mailer = null;

    protected function tearDown(): void
    {
        $this->app = null;
        parent::tearDown();
    }

    protected static function json(string $method, string $path, array $body = []): ServerRequestInterface
    {
        $request = self::request($method, $path)
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json');
        $request->getBody()->write(json_encode($body, JSON_THROW_ON_ERROR));
        return $request;
    }

    protected static function html(string $method, string $path, array $body = []): ServerRequestInterface
    {
        $request = self::request($method, $path)
            ->withHeader('Accept', 'text/html')
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded');
        $request->getBody()->write(http_build_query($body));
        return $request;
    }

    protected static function request(string $method, string $path): ServerRequestInterface
    {
        return (new ServerRequestFactory())->createServerRequest($method, $path);
    }

    /**
     * @param array<int|string,string> $fixtures
     */
    protected function loadFixtures(array $fixtures): void
    {
        /** @var ContainerInterface $container */
        $container = $this->app()->getContainer();
        $loader = new Loader();
        foreach ($fixtures as $class) {
            /** @var AbstractFixture $fixture */
            $fixture = $container->get($class);
            $loader->addFixture($fixture);
        }
        $em = $container->get(EntityManagerInterface::class);
        $executor = new ORMExecutor($em, new ORMPurger($em));
        $executor->execute($loader->getFixtures());
    }

    protected function app(): App
    {
        if ($this->app === null) {
            $this->app = (require __DIR__ . '/../../config/app.php')($this->container());
        }
        return $this->app;
    }

    protected function mailer(): MailerClient
    {
        if ($this->mailer === null) {
            $this->mailer = new MailerClient();
        }
        return $this->mailer;
    }

    private function container(): ContainerInterface
    {
        /** @var ContainerInterface */
        return require __DIR__ . '/../../config/container.php';
    }
}
