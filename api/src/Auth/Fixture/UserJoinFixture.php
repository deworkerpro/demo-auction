<?php

declare(strict_types=1);

namespace App\Auth\Fixture;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\PasswordHash;
use App\Auth\Entity\User\Token;
use App\Auth\Entity\User\User;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Override;

final class UserJoinFixture extends AbstractFixture
{
    private const string PASSWORD_HASH = '$2y$12$qwnND33o8DGWvFoepotSju7eTAQ6gzLD/zy6W8NCVtiHPbkybz.w6';

    #[Override]
    public function load(ObjectManager $manager): void
    {
        $user = User::requestJoinByEmail(
            Id::generate(),
            new DateTimeImmutable('-1 hours'),
            new Email('join-existing@app.test'),
            new PasswordHash(self::PASSWORD_HASH, new DateTimeImmutable('+1 day')),
            new Token('00000000-0000-0000-0000-100000000001', new DateTimeImmutable('+1 hour'))
        );
        $manager->persist($user);

        $manager->flush();
    }
}
