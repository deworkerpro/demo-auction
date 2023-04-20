<?php

declare(strict_types=1);

namespace App\OAuth\Command\LogOut;

use App\OAuth\Entity\AuthCodeRepository;
use App\OAuth\Entity\RefreshTokenRepository;

final class Handler
{
    public function __construct(
        private readonly AuthCodeRepository $authCodes,
        private readonly RefreshTokenRepository $refreshTokens
    ) {}

    public function handle(Command $command): void
    {
        $this->authCodes->removeAllForUser($command->userId);
        $this->refreshTokens->removeAllForUser($command->userId);
    }
}
