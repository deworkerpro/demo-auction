<?php

declare(strict_types=1);

namespace App\EventStore\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Entity]
#[ORM\Table(name: 'event_store_events')]
final class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $date;

    #[ORM\Column(type: 'string')]
    private string $type;

    #[ORM\Column(type: 'json')]
    private array $payload;

    public function __construct(DateTimeImmutable $date, string $type, array $payload)
    {
        Assert::notEmpty($type);

        $this->date = $date;
        $this->type = $type;
        $this->payload = $payload;
    }

    public function getId(): int
    {
        return $this->id ?? 0;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }
}
