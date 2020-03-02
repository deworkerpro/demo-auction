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
    private string $frontendUrl;

    public function __construct(Swift_Mailer $mailer, string $frontendUrl)
    {
        $this->mailer = $mailer;
        $this->frontendUrl = $frontendUrl;
    }

    public function send(Email $email, Token $token): void
    {
        $message = (new Swift_Message('Join Confirmation'))
            ->setTo($email->getValue())
            ->setBody($this->frontendUrl . '/join/confirm?' . http_build_query([
                'token' => $token->getValue(),
            ]));

        if ($this->mailer->send($message) === 0) {
            throw new RuntimeException('Unable to send email.');
        }
    }
}
