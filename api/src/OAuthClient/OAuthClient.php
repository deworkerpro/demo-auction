<?php

declare(strict_types=1);

namespace App\OAuthClient;

use RuntimeException;
use Webmozart\Assert\Assert;

final readonly class OAuthClient
{
    /**
     * @param Provider\Provider[] $providers
     */
    public function __construct(
        private array $providers
    ) {}

    public function generateAuthUrl(string $name, string $state): string
    {
        Assert::notEmpty($name);
        Assert::notEmpty($state);

        foreach ($this->providers as $provider) {
            if ($provider->isFor($name)) {
                return $provider->generateAuthUrl($state);
            }
        }

        throw new RuntimeException('Unknown provider "' . $name . '".');
    }

    public function getIdentity(string $name, string $code): Identity
    {
        Assert::notEmpty($name);
        Assert::notEmpty($code);

        foreach ($this->providers as $provider) {
            if ($provider->isFor($name)) {
                return $provider->getIdentity($code);
            }
        }

        throw new RuntimeException('Unknown provider "' . $name . '".');
    }
}
