<?php

declare(strict_types=1);

namespace App\Auth\Query\FindIdByCredentials;

use App\Auth\Entity\User\Status;
use App\Auth\Service\PasswordHasher;
use Doctrine\DBAL\Connection;

final class Fetcher
{
    private readonly Connection $connection;
    private readonly PasswordHasher $hasher;

    public function __construct(Connection $connection, PasswordHasher $hasher)
    {
        $this->connection = $connection;
        $this->hasher = $hasher;
    }

    public function fetch(Query $query): ?User
    {
        $result = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'status',
                'password_hash',
            )
            ->from('auth_users')
            ->where('email = :email')
            ->setParameter('email', mb_strtolower($query->email))
            ->executeQuery();

        /**
         * @var array{
         *     id: string,
         *     status: string,
         *     password_hash: ?string,
         * }|false $row
         */
        $row = $result->fetchAssociative();

        if ($row === false) {
            return null;
        }

        $hash = $row['password_hash'];

        if ($hash === null) {
            return null;
        }

        if (!$this->hasher->validate($query->password, $hash)) {
            return null;
        }

        return new User(
            id: $row['id'],
            isActive: $row['status'] === Status::ACTIVE
        );
    }
}
