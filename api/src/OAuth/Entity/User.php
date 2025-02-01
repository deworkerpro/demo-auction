<?php

declare(strict_types=1);

namespace App\OAuth\Entity;

use League\OAuth2\Server\Entities\UserEntityInterface;
use Override;
use Webmozart\Assert\Assert;

final readonly class User implements UserEntityInterface
{
    /**
     * @var non-empty-string
     */
    private string $identifier;

    public function __construct(string $identifier)
    {
        Assert::notEmpty($identifier);
        Assert::uuid($identifier);

        $this->identifier = $identifier;
    }

    #[Override]
    public function getIdentifier(): string
    {
        return $this->identifier;
    }
}
