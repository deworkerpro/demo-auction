<?php

declare(strict_types=1);

namespace App\OAuth\Console;

use App\OAuth\Command\ClearExpiredItems\Command;
use App\OAuth\Command\ClearExpiredItems\Handler;
use Override;
use Symfony\Component\Console\Command\Command as ConsoleCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ClearExpiredCommand extends ConsoleCommand
{
    public function __construct(private readonly Handler $handler)
    {
        parent::__construct();
    }

    #[Override]
    protected function configure(): void
    {
        $this
            ->setName('oauth:clear-expired');
    }

    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->handler->handle(new Command(date(DATE_ATOM)));

        return 0;
    }
}
