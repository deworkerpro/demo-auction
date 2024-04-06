<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class StatusType extends StringType
{
    public const NAME = 'auth_user_status';

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): mixed
    {
        return $value instanceof Status ? $value->getName() : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Status
    {
        return !empty($value) ? new Status((string)$value) : null;
    }
}
