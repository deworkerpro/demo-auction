<?php

declare(strict_types=1);

namespace App\Newsletter\Query\Subscription;

use Doctrine\DBAL\Connection;

final class Fetcher
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function fetch(string $id): ?Subscription
    {
        $result = $this->connection->createQueryBuilder()
            ->select(['id', 'email'])
            ->from('newsletter_subscriptions')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->executeQuery();

        /** @var array{id: string, email: string}|false */
        $row = $result->fetchAssociative();

        if ($row === false) {
            return null;
        }

        return new Subscription(
            id: $row['id'],
            email: $row['email']
        );
    }
}
