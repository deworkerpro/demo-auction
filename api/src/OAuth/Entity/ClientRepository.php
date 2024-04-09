<?php

declare(strict_types=1);

namespace App\OAuth\Entity;

use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use Override;

final readonly class ClientRepository implements ClientRepositoryInterface
{
    /**
     * @param Client[] $clients
     */
    public function __construct(private array $clients) {}

    #[Override]
    public function getClientEntity($clientIdentifier): ?Client
    {
        foreach ($this->clients as $client) {
            if ($client->getIdentifier() === $clientIdentifier) {
                return $client;
            }
        }

        return null;
    }

    #[Override]
    public function validateClient($clientIdentifier, $clientSecret, $grantType): bool
    {
        $client = $this->getClientEntity($clientIdentifier);

        if ($client === null) {
            return false;
        }

        if ($clientSecret !== null) {
            return false;
        }

        return true;
    }
}
