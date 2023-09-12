<?php

declare(strict_types=1);

namespace App\EventStore;

use App\EventStore\Entity\EventRepository;
use App\EventStore\ExchangeResolver\ExchangeResolver;
use App\Queue\Message;
use App\Queue\Publisher;

final readonly class EventsEmitter
{
    public function __construct(
        private EventRepository $events,
        private Publisher $publisher,
        private ExchangeResolver $exchanges
    ) {}

    public function emitNewEvents(): void
    {
        $events = $this->events->allSince(0);

        foreach ($events as $event) {
            $exchange = $this->exchanges->resolveForEvent($event->getType());

            $this->publisher->publish($exchange, new Message(
                id: $event->getId(),
                type: $event->getType(),
                payload: $event->getPayload(),
            ));
        }
    }
}
