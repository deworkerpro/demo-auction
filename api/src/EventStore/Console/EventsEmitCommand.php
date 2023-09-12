<?php

declare(strict_types=1);

namespace App\EventStore\Console;

use App\EventStore\EventsEmitter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class EventsEmitCommand extends Command
{
    public function __construct(private readonly EventsEmitter $emitter)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('events:emit')
            ->setDescription('Emit events');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<comment>Emitting events</comment>');

        $this->emitter->emitNewEvents();

        $output->writeln('<info>Done!</info>');

        return 0;
    }
}
