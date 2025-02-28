<?php

declare(strict_types=1);

namespace Test\Functional;

use GuzzleHttp\Client;

final readonly class WiremockClient
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'http://wiremock:8080',
        ]);
    }

    public function reset(): void
    {
        $this->client->post('/__admin/mappings/reset');
    }

    public function addMapping(array $mapping): void
    {
        $this->client->post('/__admin/mappings', [
            'json' => $mapping,
        ]);
    }
}
