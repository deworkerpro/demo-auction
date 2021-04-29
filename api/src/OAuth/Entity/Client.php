<?php

declare(strict_types=1);

namespace App\OAuth\Entity;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\ClientTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use Webmozart\Assert\Assert;

final class Client implements ClientEntityInterface
{
    use ClientTrait;
    use EntityTrait;

    public function __construct(string $identifier, string $name, string $redirectUri)
    {
        Assert::notEmpty($identifier);
        Assert::notEmpty($name);
        Assert::notEmpty($redirectUri);

        $this->setIdentifier($identifier);
        $this->name = $name;
        $this->redirectUri = $redirectUri;
    }
}
