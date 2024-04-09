<?php

declare(strict_types=1);

namespace App\Auth\Command\ResetPassword\Request;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\UserRepository;
use App\Auth\Service\PasswordResetTokenSender;
use App\Auth\Service\Tokenizer;
use App\Flusher;
use DateTimeImmutable;

final readonly class Handler
{
    public function __construct(
        private UserRepository $users,
        private Tokenizer $tokenizer,
        private Flusher $flusher,
        private PasswordResetTokenSender $sender
    ) {}

    public function handle(Command $command): void
    {
        $email = new Email($command->email);

        $user = $this->users->getByEmail($email);

        $date = new DateTimeImmutable();

        $user->requestPasswordReset(
            $token = $this->tokenizer->generate($date),
            $date
        );

        $this->flusher->flush();

        $this->sender->send($email, $token);
    }
}
