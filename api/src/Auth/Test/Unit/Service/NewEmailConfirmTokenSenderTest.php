<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Service;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Token;
use App\Auth\Service\NewEmailConfirmTokenSender;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email as MimeEmail;
use Twig\Environment;

/**
 * @internal
 */
#[CoversClass(NewEmailConfirmTokenSender::class)]
final class NewEmailConfirmTokenSenderTest extends TestCase
{
    public function testSuccess(): void
    {
        $to = new Email('user@app.test');
        $token = new Token(Uuid::uuid4()->toString(), new DateTimeImmutable());
        $confirmUrl = 'http://test/email/confirm?token=' . $token->getValue();

        $twig = $this->createMock(Environment::class);
        $twig->expects(self::once())->method('render')
            ->with('auth/email/confirm.html.twig', ['token' => $token])
            ->willReturn($body = '<a href="' . $confirmUrl . '">' . $confirmUrl . '</a>');

        $mailer = $this->createMock(MailerInterface::class);
        $mailer->expects(self::once())->method('send')
            ->willReturnCallback(static function (MimeEmail $message) use ($to, $body): void {
                self::assertSame($to->getValue(), $message->getTo()[0]->getAddress());
                self::assertSame('New Email Confirmation', $message->getSubject());
                self::assertSame($body, $message->getHtmlBody());
            });

        $sender = new NewEmailConfirmTokenSender($mailer, $twig);

        $sender->send($to, $token);
    }
}
