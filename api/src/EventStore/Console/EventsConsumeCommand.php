<?php

declare(strict_types=1);

namespace App\EventStore\Console;

use App\Queue\Consumer;
use App\Queue\Message;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class EventsConsumeCommand extends Command
{
    public function __construct(
        private readonly Consumer $consumer
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('events:consume')
            ->setDescription('Consume events')
            ->addArgument('queue', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $queue = (string)$input->getArgument('queue');
        $output->writeln('<comment>Listen queue ' . $queue . '</comment>');

        $this->consumer->consume($queue, static function (Message $message) use ($output): void {
            $output->writeln('<info>Consume message ' . $message->type . ':' . $message->id . '</info>');
        });

        $output->writeln('<info>Done!</info>');

        return 0;
    }
}
