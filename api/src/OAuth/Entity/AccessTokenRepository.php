<?php

declare(strict_types=1);

namespace App\OAuth\Entity;

use App\Auth\Query\FindIdentityById\Fetcher;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Override;

final readonly class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    public function __construct(private Fetcher $users) {}

    #[Override]
    public function getNewToken(
        ClientEntityInterface $clientEntity,
        array $scopes,
        ?string $userIdentifier = null
    ): AccessToken {
        $accessToken = new AccessToken($clientEntity, $scopes);

        if ($userIdentifier !== null) {
            $identity = $this->users->fetch($userIdentifier);

            if ($identity === null) {
                throw new OAuthServerException('User is not found.', 101, 'invalid_user', 401);
            }

            $accessToken->setUserIdentifier($identity->id);
            $accessToken->setUserRole($identity->role);
        }

        return $accessToken;
    }

    #[Override]
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity): void
    {
        // do nothing
    }

    #[Override]
    public function revokeAccessToken(string $tokenId): void
    {
        // do nothing
    }

    #[Override]
    public function isAccessTokenRevoked(string $tokenId): bool
    {
        return false;
    }
}
