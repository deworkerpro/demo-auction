<?php

declare(strict_types=1);

namespace App\OAuthClient\Provider;

use App\OAuthClient\Identity;
use GuzzleHttp\Client;
use Override;
use Webmozart\Assert\Assert;

final readonly class Yandex implements Provider
{
    public function __construct(
        private Client $client,
        private string $authUrl,
        private string $callbackUrl,
        private string $tokenUrl,
        private string $apiUrl,
        private string $clientId,
        private string $clientSecret
    ) {
        Assert::notEmpty($authUrl);
        Assert::notEmpty($tokenUrl);
        Assert::notEmpty($callbackUrl);
        Assert::notEmpty($tokenUrl);
        Assert::notEmpty($apiUrl);
        Assert::notEmpty($clientId);
        Assert::notEmpty($clientSecret);
    }

    #[Override]
    public function isFor(string $name): bool
    {
        return $name === 'yandex';
    }

    #[Override]
    public function generateAuthUrl(string $state): string
    {
        return $this->authUrl . '/authorize?' . http_build_query([
            'client_id' => $this->clientId,
            'redirect_uri' => $this->callbackUrl . '/oauth/yandex',
            'response_type' => 'code',
            'scope' => 'login:email',
            'state' => $state,
        ]);
    }

    #[Override]
    public function getIdentity(string $code): Identity
    {
        Assert::notEmpty($code);

        $response = $this->client->post($this->tokenUrl . '/token', [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'form_params' => [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'grant_type' => 'authorization_code',
                'code' => $code,
            ],
        ]);

        /**
         * @var array{access_token: string, token_type: string} $data
         */
        $data = json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);

        $response = $this->client->get($this->apiUrl . '/info', [
            'headers' => [
                'Authorization' => $data['token_type'] . ' ' . $data['access_token'],
                'Accept' => 'application/json',
            ],
        ]);

        /**
         * @var array{default_email: string, id: string} $data
         */
        $data = json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);

        return new Identity(
            $data['id'],
            $data['default_email']
        );
    }
}
