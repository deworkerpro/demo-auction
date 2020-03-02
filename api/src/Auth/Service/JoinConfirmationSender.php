<?php

declare(strict_types=1);

namespace App\Auth\Service;

use App\Auth\Entity\User\Token;
use App\Auth\Entity\User\Email;
use RuntimeException;
use Swift_Mailer;
use Swift_Message;

class JoinConfirmationSender
{
    private Swift_Mailer $mailer;
    private array $from;

    public function __construct(Swift_Mailer $mailer, array $from)
    {
        $this->mailer = $mailer;
        $this->from = $from;
    }

    public function send(Email $email, Token $token): void
    {
        $message = (new Swift_Message('Join Confirmation'))
            ->setFrom($this->from)
            ->setTo($email->getValue())
            ->setBody('/join/confirm?' . http_build_query([
                'token' => $token->getValue(),
            ]));

        if ($this->mailer->send($message) === 0) {
            throw new RuntimeException('Unable to send email.');
        }
    }
}
