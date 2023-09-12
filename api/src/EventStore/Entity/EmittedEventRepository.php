<?php

declare(strict_types=1);

namespace App\EventStore\Entity;

use Doctrine\DBAL\Connection;
use Webmozart\Assert\Assert;

final readonly class EmittedEventRepository
{
    public function __construct(
        private Connection $connection
    ) {}

    public function getLastSendId(): int
    {
        return (int)$this->connection->createQueryBuilder()
            ->select(['MAX(event_id)'])
            ->from('event_store_emitted_events')
            ->executeQuery()->fetchOne();
    }

    public function markAsSent(string $exchange, int $eventId): void
    {
        Assert::notEmpty($exchange);

        $this->connection->insert('event_store_emitted_events', [
            'exchange' => $exchange,
            'event_id' => $eventId,
        ]);
    }
}
