<?php

declare(strict_types=1);

namespace App\Auth\Service;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Token;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email as MimeEmail;
use Twig\Environment;

final class JoinConfirmationSender
{
    private MailerInterface $mailer;
    private Environment $twig;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function send(Email $email, Token $token): void
    {
        $message = (new MimeEmail())
            ->subject('Join Confirmation')
            ->to($email->getValue())
            ->html($this->twig->render('auth/join/confirm.html.twig', ['token' => $token]), 'text/html');

        $this->mailer->send($message);
    }
}
