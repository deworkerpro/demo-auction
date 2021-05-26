<?php

declare(strict_types=1);

namespace App\OAuth\Command\ClearExpiredItems;

use App\OAuth\Entity\AuthCodeRepository;
use App\OAuth\Entity\RefreshTokenRepository;
use DateTimeImmutable;

final class Handler
{
    private AuthCodeRepository $authCodes;
    private RefreshTokenRepository $refreshTokens;

    public function __construct(AuthCodeRepository $authCodes, RefreshTokenRepository $refreshTokens)
    {
        $this->authCodes = $authCodes;
        $this->refreshTokens = $refreshTokens;
    }

    public function handle(Command $command): void
    {
        $date = new DateTimeImmutable($command->date);

        $this->authCodes->removeAllExpired($date);
        $this->refreshTokens->removeAllExpired($date);
    }
}
