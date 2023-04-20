<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangeEmail\Request;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\UserRepository;
use App\Auth\Service\NewEmailConfirmTokenSender;
use App\Auth\Service\Tokenizer;
use App\Flusher;
use DateTimeImmutable;
use DomainException;

final class Handler
{
    public function __construct(
        private readonly UserRepository $users,
        private readonly Tokenizer $tokenizer,
        private readonly NewEmailConfirmTokenSender $sender,
        private readonly Flusher $flusher
    ) {}

    public function handle(Command $command): void
    {
        $user = $this->users->get(new Id($command->id));

        $email = new Email($command->email);

        if ($this->users->hasByEmail($email)) {
            throw new DomainException('Email is already in use.');
        }

        $date = new DateTimeImmutable();

        $user->requestEmailChanging(
            $token = $this->tokenizer->generate($date),
            $date,
            $email,
        );

        $this->flusher->flush();

        $this->sender->send($email, $token);
    }
}
