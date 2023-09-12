<?php

declare(strict_types=1);

namespace App\EventStore\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'event_store_consumed_events')]
#[ORM\Index(columns: ['queue', 'type', 'event_id'])]
final class ConsumedEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string')]
    private string $queue;

    #[ORM\Column(type: 'string')]
    private string $type;

    #[ORM\Column(type: 'integer')]
    private int $eventId;

    public function __construct(string $queue, string $type, int $eventId)
    {
        $this->queue = $queue;
        $this->type = $type;
        $this->eventId = $eventId;
    }

    public function getId(): int
    {
        return $this->id ?? 0;
    }

    public function getEventId(): int
    {
        return $this->eventId;
    }

    public function getQueue(): string
    {
        return $this->queue;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
