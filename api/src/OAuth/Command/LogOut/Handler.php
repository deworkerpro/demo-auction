<?php

declare(strict_types=1);

namespace App\OAuth\Command\LogOut;

use App\OAuth\Entity\AuthCodeRepository;
use App\OAuth\Entity\RefreshTokenRepository;

final readonly class Handler
{
    public function __construct(
        private AuthCodeRepository $authCodes,
        private RefreshTokenRepository $refreshTokens
    ) {}

    public function handle(Command $command): void
    {
        $this->authCodes->removeAllForUser($command->userId);
        $this->refreshTokens->removeAllForUser($command->userId);
    }
}
