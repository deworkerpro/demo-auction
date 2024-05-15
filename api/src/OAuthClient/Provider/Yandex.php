<?php

declare(strict_types=1);

namespace App\OAuthClient\Provider;

use Override;
use Webmozart\Assert\Assert;

final readonly class Yandex implements Provider
{
    public function __construct(
        private string $authUrl,
        private string $callbackUrl,
        private string $clientId
    ) {
        Assert::notEmpty($authUrl);
        Assert::notEmpty($callbackUrl);
        Assert::notEmpty($clientId);
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
}
