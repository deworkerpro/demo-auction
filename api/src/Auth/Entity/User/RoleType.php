<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class RoleType extends StringType
{
    public const NAME = 'auth_user_role';

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): mixed
    {
        return $value instanceof Role ? $value->getName() : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Role
    {
        return !empty($value) ? new Role((string)$value) : null;
    }
}
