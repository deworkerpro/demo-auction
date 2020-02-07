<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangePassword;

use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\UserRepository;
use App\Auth\Service\PasswordHasher;
use App\Flusher;

class Handler
{
    private UserRepository $users;
    private PasswordHasher $hasher;
    private Flusher $flusher;

    public function __construct(
        UserRepository $users,
        PasswordHasher $hasher,
        Flusher $flusher
    ) {
        $this->users = $users;
        $this->hasher = $hasher;
        $this->flusher = $flusher;
    }

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
