<?php

declare(strict_types=1);

namespace App\EventStore\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'event_store_emitted_events')]
final class EmittedEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'integer')]
    private int $eventId;

    #[ORM\Column(type: 'string')]
    private string $exchange;

    public function __construct(int $eventId, string $exchange)
    {
        $this->eventId = $eventId;
        $this->exchange = $exchange;
    }

    public function getId(): int
    {
        return $this->id ?? 0;
    }

    public function getEventId(): int
    {
        return $this->eventId;
    }

    public function getExchange(): string
    {
        return $this->exchange;
    }
}
