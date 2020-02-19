<?php

declare(strict_types=1);

namespace App\Auth\Command\AttachNetwork;

use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\Network;
use App\Auth\Entity\User\UserRepository;
use App\Flusher;

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
        $network = new Network($command->network, $command->identity);

        if ($this->users->hasByNetwork($network)) {
            throw new \DomainException('User with this network already exists.');
        }

        $user = $this->users->get(new Id($command->id));

        $user->attachNetwork($network);

        $this->flusher->flush();
    }
}
