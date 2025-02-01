<?php

declare(strict_types=1);

namespace App\Auth\Query\FindIdentityById;

use Doctrine\DBAL\Connection;

final readonly class Fetcher
{
    public function __construct(private Connection $connection) {}

    public function fetch(string $id): ?Identity
    {
        $result = $this->connection->createQueryBuilder()
            ->select('id', 'role')
            ->from('auth_users')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->executeQuery();

        /**
         * @var array{
         *     id: non-empty-string,
         *     role: non-empty-string
         * }|false $row
         */
        $row = $result->fetchAssociative();

        if ($row === false) {
            return null;
        }

        return new Identity(
            id: $row['id'],
            role: $row['role']
        );
    }
}
