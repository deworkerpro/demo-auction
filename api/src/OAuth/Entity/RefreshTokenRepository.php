<?php

declare(strict_types=1);

namespace App\OAuth\Entity;

use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

final class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    public function getNewRefreshToken(): ?RefreshToken
    {
        return new RefreshToken();
    }

    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity): void
    {
        // TODO: Implement persisting
    }

    public function revokeRefreshToken($tokenId): void
    {
        // TODO: Implement revoking
    }

    public function isRefreshTokenRevoked($tokenId): bool
    {
        // TODO: Implement revoking

        return false;
    }
}
