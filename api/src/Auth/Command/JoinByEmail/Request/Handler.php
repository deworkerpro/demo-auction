<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByEmail\Request;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\User;
use App\Auth\Entity\User\UserRepository;
use App\Auth\Service\JoinConfirmationSender;
use App\Auth\Service\PasswordHasher;
use App\Auth\Service\Tokenizer;
use App\Flusher;
use DateTimeImmutable;
use DomainException;

class Handler
{
    private UserRepository $users;
    private PasswordHasher $hasher;
    private Tokenizer $tokenizer;
    private Flusher $flusher;
    private JoinConfirmationSender $sender;

    public function __construct(
        UserRepository $users,
        PasswordHasher $hasher,
        Tokenizer $tokenizer,
        Flusher $flusher,
        JoinConfirmationSender $sender
    ) {
        $this->users = $users;
        $this->hasher = $hasher;
        $this->tokenizer = $tokenizer;
        $this->flusher = $flusher;
        $this->sender = $sender;
    }

    public function handle(Command $command): void
    {
        $email = new Email($command->email);

        if ($this->users->hasByEmail($email)) {
            throw new DomainException('User already exists.');
        }

        $date = new DateTimeImmutable();

        $user = User::requestJoinByEmail(
            Id::generate(),
            $date,
            $email,
            $this->hasher->hash($command->password),
            $token = $this->tokenizer->generate($date)
        );

        $this->users->add($user);

        $this->flusher->flush();

        $this->sender->send($email, $token);
    }
}
