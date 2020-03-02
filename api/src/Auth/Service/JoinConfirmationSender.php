<?php

declare(strict_types=1);

namespace App\Auth\Service;

use App\Auth\Entity\User\Token;
use App\Auth\Entity\User\Email;
use App\Frontend\FrontendUrlGenerator;
use RuntimeException;
use Swift_Mailer;
use Swift_Message;

class JoinConfirmationSender
{
    private Swift_Mailer $mailer;
    private FrontendUrlGenerator $frontend;

    public function __construct(Swift_Mailer $mailer, FrontendUrlGenerator $frontend)
    {
        $this->mailer = $mailer;
        $this->frontend = $frontend;
    }

    public function send(Email $email, Token $token): void
    {
        $message = (new Swift_Message('Join Confirmation'))
            ->setTo($email->getValue())
            ->setBody($this->frontend->generate('join/confirm', [
                'token' => $token->getValue(),
            ]));

        if ($this->mailer->send($message) === 0) {
            throw new RuntimeException('Unable to send email.');
        }
    }
}
