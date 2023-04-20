<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangePassword;

use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\UserRepository;
use App\Auth\Service\PasswordHasher;
use App\Flusher;

final class Handler
{
    public function __construct(
        private readonly UserRepository $users,
        private readonly PasswordHasher $hasher,
        private readonly Flusher $flusher
    ) {}

    public function handle(Command $command): void
    {
        $user = $this->users->get(new Id($command->id));

        $user->changePassword(
            $command->current,
            $command->new,
            $this->hasher
        );

        $this->flusher->flush();
    }
}
