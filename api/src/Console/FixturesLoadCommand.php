<?php

declare(strict_types=1);

namespace App\Console;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Override;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class FixturesLoadCommand extends Command
{
    /**
     * @param string[] $paths
     */
    public function __construct(private readonly EntityManagerInterface $em, private readonly array $paths)
    {
        parent::__construct();
    }

    #[Override]
    protected function configure(): void
    {
        $this
            ->setName('fixtures:load')
            ->setDescription('Load fixtures');
    }

    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<comment>Loading fixtures</comment>');

        $loader = new Loader();

        foreach ($this->paths as $path) {
            $loader->loadFromDirectory($path);
        }

        $executor = new ORMExecutor($this->em, new ORMPurger());

        $executor->setLogger(static function (string $message) use ($output): void {
            $output->writeln($message);
        });

        $executor->execute($loader->getFixtures());

        $output->writeln('<info>Done!</info>');

        return 0;
    }
}
