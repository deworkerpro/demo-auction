<?php

declare(strict_types=1);

namespace App\EventStore\Console;

use App\EventStore\Entity\ConsumedEventRepository;
use App\EventStore\EventListenerResolver\EventListenerResolver;
use App\EventStore\EventSerializer;
use App\Queue\Consumer;
use App\Queue\Message;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class EventsConsumeCommand extends Command
{
    public function __construct(
        private readonly Consumer $consumer,
        private readonly EventSerializer $serializer,
        private readonly ConsumedEventRepository $consumed,
        private readonly EventListenerResolver $listenerResolver
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

        $this->consumer->consume($queue, function (Message $message) use ($queue, $output): void {
            if ($this->consumed->hasConsumed($queue, $message->type, $message->id)) {
                $output->writeln('<info>Skip message ' . $message->type . ':' . $message->id . '</info>');
                return;
            }
            $output->writeln('<info>Consume message ' . $message->type . ':' . $message->id . '</info>');
            $event = $this->serializer->unserialize($message->type, $message->payload);
            $listener = $this->listenerResolver->resolve($queue, $event);
            $listener($event);
            $this->consumed->markAsConsumed($queue, $message->type, $message->id);
        });

        $output->writeln('<info>Done!</info>');

        return 0;
    }
}
