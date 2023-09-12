<?php

declare(strict_types=1);

namespace App\EventStore\Entity;

use Doctrine\DBAL\Connection;
use Webmozart\Assert\Assert;

final readonly class ConsumedEventRepository
{
    public function __construct(
        private Connection $connection
    ) {}

    public function hasConsumed(string $queue, string $type, int $eventId): bool
    {
        return $this->connection->createQueryBuilder()
            ->select(['COUNT(id)'])
            ->from('event_store_consumed_events')
            ->andWhere('queue = :queue AND type = :type AND event_id = :event_id')
            ->setParameter('queue', $queue)
            ->setParameter('type', $type)
            ->setParameter('event_id', $eventId)
            ->executeQuery()->fetchOne() > 0;
    }

    public function markAsConsumed(string $queue, string $type, int $eventId): void
    {
        Assert::notEmpty($queue);

        $this->connection->insert('event_store_consumed_events', [
            'queue' => $queue,
            'type' => $type,
            'event_id' => $eventId,
        ]);
    }
}
