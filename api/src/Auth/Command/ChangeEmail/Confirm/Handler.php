<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangeEmail\Confirm;

use App\Auth\Entity\User\UserRepository;
use App\Flusher;
use DateTimeImmutable;
use DomainException;

final class Handler
{
    public function __construct(
        private readonly UserRepository $users,
        private readonly Flusher $flusher
    ) {}

    public function handle(Command $command): void
    {
        if (!$user = $this->users->findByNewEmailToken($command->token)) {
            throw new DomainException('Incorrect token.');
        }

        $user->confirmEmailChanging(
            $command->token,
            new DateTimeImmutable()
        );

        $this->flusher->flush();
    }
}
