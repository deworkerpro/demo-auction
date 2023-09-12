<?php

declare(strict_types=1);

namespace App\Auth\Query\FindContactById;

use Doctrine\DBAL\Connection;

final readonly class Fetcher
{
    public function __construct(public Connection $connection) {}

    public function fetch(string $id): ?Contact
    {
        $result = $this->connection->createQueryBuilder()
            ->select(['id', 'email'])
            ->from('auth_users')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->executeQuery();

        /** @var array{id: string, email: string}|false */
        $row = $result->fetchAssociative();

        if ($row === false) {
            return null;
        }

        return new Contact(
            id: $row['id'],
            email: $row['email']
        );
    }
}
