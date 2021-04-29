<?php

declare(strict_types=1);

namespace App\OAuth\Entity;

use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;

final class AuthCodeRepository implements AuthCodeRepositoryInterface
{
    public function getNewAuthCode(): AuthCode
    {
        return new AuthCode();
    }

    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity): void
    {
        // TODO: Implement persisting
    }

    public function revokeAuthCode($codeId): void
    {
        // TODO: Implement revoking
    }

    public function isAuthCodeRevoked($codeId): bool
    {
        // TODO: Implement revoking

        return false;
    }
}
