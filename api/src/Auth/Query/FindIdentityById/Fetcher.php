<?php

declare(strict_types=1);

namespace App\Auth\Query\FindIdentityById;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Result;

final class Fetcher
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function fetch(string $id): ?Identity
    {
        /** @var Result $stmt */
        $stmt = $this->connection->createQueryBuilder()
            ->select(['id', 'role'])
            ->from('auth_users')
            ->where('id = :id')
            ->setParameter(':id', $id)
            ->execute();

        /** @var array{id: string, role: string}|false */
        $row = $stmt->fetchAssociative();

        if ($row === false) {
            return null;
        }

        return new Identity(
            id: $row['id'],
            role: $row['role']
        );
    }
}
