<?php

declare(strict_types=1);

namespace App\EventStore;

use App\EventStore\Entity\EmittedEventRepository;
use App\EventStore\Entity\EventRepository;
use App\EventStore\ExchangeResolver\ExchangeResolver;
use App\Queue\Message;
use App\Queue\Publisher;

final readonly class EventsEmitter
{
    public function __construct(
        private EventRepository $events,
        private Publisher $publisher,
        private EmittedEventRepository $emitted,
        private ExchangeResolver $exchanges
    ) {}

    public function emitNewEvents(): void
    {
        $lastId = $this->emitted->getLastSendId();

        $events = $this->events->allSince($lastId);

        foreach ($events as $event) {
            $exchange = $this->exchanges->resolveForEvent($event->getType());

            $this->publisher->publish($exchange, new Message(
                id: $event->getId(),
                type: $event->getType(),
                payload: $event->getPayload(),
            ));

            $this->emitted->markAsSent($exchange, $event->getId());
        }
    }
}
