<?php

declare(strict_types=1);

namespace App\OAuth\Test\Builder;

use App\OAuth\Entity\AccessToken;
use App\OAuth\Entity\Client;
use App\OAuth\Entity\Scope;

final class AccessTokenBuilder
{
    /**
     * @var Scope[]
     */
    private array $scopes;
    private ?string $userIdentifier = null;

    public function __construct()
    {
        $this->scopes = [new Scope('common')];
    }

    public function withUserIdentifier(string $identifier): self
    {
        $clone = clone $this;
        $clone->userIdentifier = $identifier;
        return $clone;
    }

    public function build(Client $client): AccessToken
    {
        $token = new AccessToken($client, $this->scopes);

        if ($this->userIdentifier !== null) {
            $token->setUserIdentifier($this->userIdentifier);
        }

        return $token;
    }
}
