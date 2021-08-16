<?php

declare(strict_types=1);

namespace App\OAuth\Test\Builder;

use App\OAuth\Entity\Client;
use Ramsey\Uuid\Uuid;

final class ClientBuilder
{
    private string $identifier;
    private string $name;
    private string $redirectUri;

    public function __construct()
    {
        $this->identifier = Uuid::uuid4()->toString();
        $this->name = 'Client';
        $this->redirectUri = 'http://localhost/auth';
    }

    public function build(): Client
    {
        return new Client(
            $this->identifier,
            $this->name,
            $this->redirectUri,
        );
    }
}
