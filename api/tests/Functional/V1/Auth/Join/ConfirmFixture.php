<?php

declare(strict_types=1);

namespace Test\Functional\V1\Auth\Join;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\PasswordHash;
use App\Auth\Entity\User\Token;
use App\Auth\Entity\User\User;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Override;

final class ConfirmFixture extends AbstractFixture
{
    public const string VALID = '00000000-0000-0000-0000-000000000001';
    public const string EXPIRED = '00000000-0000-0000-0000-000000000002';

    #[Override]
    public function load(ObjectManager $manager): void
    {
        // Valid

        $user = User::requestJoinByEmail(
            Id::generate(),
            $date = new DateTimeImmutable(),
            new Email('valid@app.test'),
            new PasswordHash('password-hash', new DateTimeImmutable('+1 day')),
            new Token($value = self::VALID, $date->modify('+1 hour'))
        );

        $manager->persist($user);

        // Expired

        $user = User::requestJoinByEmail(
            Id::generate(),
            $date = new DateTimeImmutable(),
            new Email('expired@app.test'),
            new PasswordHash('password-hash', new DateTimeImmutable('+1 day')),
            new Token($value = self::EXPIRED, $date->modify('-2 hours'))
        );

        $manager->persist($user);

        $manager->flush();
    }
}
