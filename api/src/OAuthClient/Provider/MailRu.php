<?php

declare(strict_types=1);

namespace App\OAuthClient\Provider;

use App\OAuthClient\Identity;
use GuzzleHttp\Client;
use Override;
use Webmozart\Assert\Assert;

final readonly class MailRu implements Provider
{
    public function __construct(
        private Client $client,
        private string $authUrl,
        private string $tokenUrl,
        private string $apiUrl,
        private string $clientId,
        private string $clientSecret,
        private string $callbackUrl
    ) {
        Assert::notEmpty($authUrl);
        Assert::notEmpty($apiUrl);
        Assert::notEmpty($tokenUrl);
        Assert::notEmpty($clientId);
        Assert::notEmpty($clientSecret);
        Assert::notEmpty($callbackUrl);
    }

    #[Override]
    public function isFor(string $name): bool
    {
        return $name === 'mailru';
    }

    #[Override]
    public function generateAuthUrl(string $state): string
    {
        return $this->authUrl . '/login?' . http_build_query([
            'client_id' => $this->clientId,
            'redirect_uri' => $this->callbackUrl . '/oauth/mailru',
            'response_type' => 'code',
            'scope' => 'userinfo',
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
                'redirect_uri' => $this->callbackUrl . '/oauth/mailru',
                'grant_type' => 'authorization_code',
                'code' => $code,
            ],
        ]);

        /**
         * @var array{access_token: string} $data
         */
        $data = json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);

        $response = $this->client->get($this->apiUrl . '/userinfo?' . http_build_query([
            'access_token' => $data['access_token'],
        ]), [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        /**
         * @var array{id: string, email: string} $data
         */
        $data = json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);

        return new Identity(
            id: $data['id'],
            email: $data['email']
        );
    }
}
