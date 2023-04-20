<?php

declare(strict_types=1);

namespace App\OAuth\Entity;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

final class ScopeRepository implements ScopeRepositoryInterface
{
    /**
     * @param Scope[] $scopes
     */
    public function __construct(private readonly array $scopes) {}

    public function getScopeEntityByIdentifier($identifier): ?Scope
    {
        foreach ($this->scopes as $scope) {
            if ($scope->getIdentifier() === $identifier) {
                return $scope;
            }
        }
        return null;
    }

    public function finalizeScopes(
        array $scopes,
        $grantType,
        ClientEntityInterface $clientEntity,
        $userIdentifier = null
    ): array {
        return $scopes;
    }
}
