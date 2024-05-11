<?php

declare(strict_types=1);

namespace App\Auth\Command\ResetPassword\Reset;

use App\Auth\Entity\User\UserRepository;
use App\Auth\Service\PasswordHasher;
use App\Flusher;
use DateTimeImmutable;
use DomainException;

final readonly class Handler
{
    public function __construct(
        private UserRepository $users,
        private PasswordHasher $hasher,
        private Flusher $flusher
    ) {}

    public function handle(Command $command): void
    {
        if (!$user = $this->users->findByPasswordResetToken($command->token)) {
            throw new DomainException('Token is not found.');
        }

        $user->resetPassword(
            $command->token,
            new DateTimeImmutable(),
            $command->password,
            $this->hasher
        );

        $this->flusher->flush();
    }
}
