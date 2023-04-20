<?php

declare(strict_types=1);

namespace App\Auth\Service;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Token;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email as MimeEmail;
use Twig\Environment;

final class NewEmailConfirmTokenSender
{
    public function __construct(private readonly MailerInterface $mailer, private readonly Environment $twig) {}

    public function send(Email $email, Token $token): void
    {
        $message = (new MimeEmail())
            ->subject('New Email Confirmation')
            ->to($email->getValue())
            ->html($this->twig->render('auth/email/confirm.html.twig', ['token' => $token]), 'text/html');

        $this->mailer->send($message);
    }
}
