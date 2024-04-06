<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByNetwork;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\Network;
use App\Auth\Entity\User\User;
use App\Auth\Entity\User\UserRepository;
use App\Flusher;
use DateTimeImmutable;
use DomainException;

final class Handler
{
    public function __construct(private readonly UserRepository $users, private readonly Flusher $flusher) {}

    public function handle(Command $command): void
    {
        $network = new Network($command->network, $command->identity);
        $email = new Email($command->email);

        if ($this->users->hasByNetwork($network)) {
            throw new DomainException('User with this network already exists.');
        }

        if ($this->users->hasByEmail($email)) {
            throw new DomainException('User with this email already exists.');
        }

        $user = User::joinByNetwork(
            Id::generate(),
            new DateTimeImmutable(),
            $email,
            $network
        );

        $this->users->add($user);

        $this->flusher->flush();
    }
}
