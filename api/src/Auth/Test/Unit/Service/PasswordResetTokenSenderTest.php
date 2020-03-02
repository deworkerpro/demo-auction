<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Service;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Token;
use App\Auth\Service\PasswordResetTokenSender;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use RuntimeException;
use Swift_Mailer;
use Swift_Message;
use Twig\Environment;

/**
 * @covers PasswordResetTokenSender
 */
class PasswordResetTokenSenderTest extends TestCase
{
    public function testSuccess(): void
    {
        $to = new Email('user@app.test');
        $token = new Token(Uuid::uuid4()->toString(), new DateTimeImmutable());
        $confirmUrl = 'http://test/password/confirm?token=' . $token->getValue();

        $twig = $this->createMock(Environment::class);
        $twig->expects($this->once())->method('render')->with(
            $this->equalTo('auth/password/confirm.html.twig'),
            $this->equalTo(['token' => $token]),
        )->willReturn($body = '<a href="' . $confirmUrl . '">' . $confirmUrl . '</a>');

        $mailer = $this->createMock(Swift_Mailer::class);
        $mailer->expects($this->once())->method('send')
            ->willReturnCallback(static function (Swift_Message $message) use ($to, $body): int {
                self::assertEquals([$to->getValue() => null], $message->getTo());
                self::assertEquals('Password Reset', $message->getSubject());
                self::assertEquals($body, $message->getBody());
                self::assertEquals('text/html', $message->getBodyContentType());
                return 1;
            });

        $sender = new PasswordResetTokenSender($mailer, $twig);

        $sender->send($to, $token);
    }

    public function testError(): void
    {
        $to = new Email('user@app.test');
        $token = new Token(Uuid::uuid4()->toString(), new DateTimeImmutable());
        $confirmUrl = 'http://test/email/confirm?token=' . $token->getValue();

        $twig = $this->createStub(Environment::class);
        $twig->method('render')->willReturn('<a href="' . $confirmUrl . '">' . $confirmUrl . '</a>');

        $mailer = $this->createStub(Swift_Mailer::class);
        $mailer->method('send')->willReturn(0);

        $sender = new PasswordResetTokenSender($mailer, $twig);

        $this->expectException(RuntimeException::class);
        $sender->send($to, $token);
    }
}
