<?php

declare(strict_types=1);

namespace App\Auth\Command\Remove;

use App\Flusher;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\UserRepository;

class Handler
{
    private UserRepository $users;
    private Flusher $flusher;

    public function __construct(UserRepository $users, Flusher $flusher)
    {
        $this->users = $users;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $user = $this->users->get(new Id($command->id));

        $user->remove();

        $this->users->remove($user);

        $this->flusher->flush();
    }
}
