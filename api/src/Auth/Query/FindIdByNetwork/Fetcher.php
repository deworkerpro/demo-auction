<?php

declare(strict_types=1);

namespace App\Auth\Query\FindIdByNetwork;

use Doctrine\DBAL\Connection;

final readonly class Fetcher
{
    public function __construct(
        private Connection $connection
    ) {}

    public function fetch(string $name, string $identity): ?User
    {
        $result = $this->connection->createQueryBuilder()
            ->select('n.user_id')
            ->from('auth_user_networks', 'n')
            ->where('network_name = :name AND network_identity = :identity')
            ->setParameter('name', $name)
            ->setParameter('identity', $identity)
            ->executeQuery();

        /**
         * @var array{
         *     user_id: string,
         * }|false $row
         */
        $row = $result->fetchAssociative();

        if ($row === false) {
            return null;
        }

        return new User(
            id: $row['user_id']
        );
    }
}
