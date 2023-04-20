<?php

declare(strict_types=1);

namespace App\Auth\Command\ResetPassword\Reset;

use App\Auth\Entity\User\UserRepository;
use App\Auth\Service\PasswordHasher;
use App\Flusher;
use DateTimeImmutable;
use DomainException;

final class Handler
{
    public function __construct(
        private readonly UserRepository $users,
        private readonly PasswordHasher $hasher,
        private readonly Flusher $flusher
    ) {}

    public function handle(Command $command): void
    {
        if (!$user = $this->users->findByPasswordResetToken($command->token)) {
            throw new DomainException('Token is not found.');
        }

        $user->resetPassword(
            $command->token,
            new DateTimeImmutable(),
            $this->hasher->hash($command->password)
        );

        $this->flusher->flush();
    }
}
