<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class RoleType extends StringType
{
    public const NAME = 'auth_user_role';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof Role ? $value->getName() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new Role((string)$value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
