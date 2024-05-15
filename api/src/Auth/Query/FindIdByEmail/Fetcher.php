<?php

declare(strict_types=1);

namespace App\Auth\Query\FindIdByEmail;

use Doctrine\DBAL\Connection;

final readonly class Fetcher
{
    public function __construct(
        private Connection $connection
    ) {}

    public function fetch(string $email): ?User
    {
        $result = $this->connection->createQueryBuilder()
            ->select('u.id')
            ->from('auth_users', 'u')
            ->where('email = :email')
            ->setParameter('email', mb_strtolower($email))
            ->executeQuery();

        /**
         * @var array{
         *     id: string,
         * }|false $row
         */
        $row = $result->fetchAssociative();

        if ($row === false) {
            return null;
        }

        return new User(
            id: $row['id']
        );
    }
}
