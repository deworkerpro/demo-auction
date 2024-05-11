<?php

declare(strict_types=1);

namespace App\Auth\Query\FindIdByCredentials;

use App\Auth\Entity\User\Status;
use App\Auth\Service\PasswordHasher;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;

final readonly class Fetcher
{
    public function __construct(private Connection $connection, private PasswordHasher $hasher) {}

    public function fetch(Query $query, DateTimeImmutable $date): ?User
    {
        $result = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'status',
                'password_hash_value',
                'password_hash_expires',
            )
            ->from('auth_users')
            ->where('email = :email')
            ->setParameter('email', mb_strtolower($query->email))
            ->executeQuery();

        /**
         * @var array{
         *     id: string,
         *     status: string,
         *     password_hash_value: ?string,
         *     password_hash_expires: ?string,
         * }|false $row
         */
        $row = $result->fetchAssociative();

        if ($row === false) {
            return null;
        }

        $hash = $row['password_hash_value'];

        if ($hash === null) {
            return null;
        }

        if (!$this->hasher->validate($query->password, $hash)) {
            return null;
        }

        /**
         * @var string $expires
         */
        $expires = $row['password_hash_expires'];

        return new User(
            id: $row['id'],
            isActive: $row['status'] === Status::ACTIVE,
            isPasswordExpired: (new DateTimeImmutable($expires)) < $date
        );
    }
}
