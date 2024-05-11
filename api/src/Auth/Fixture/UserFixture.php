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
use Ramsey\Uuid\Uuid;

final class UserFixture extends AbstractFixture
{
    // 'password'
    private const string PASSWORD_HASH = '$2y$12$qwnND33o8DGWvFoepotSju7eTAQ6gzLD/zy6W8NCVtiHPbkybz.w6';

    #[Override]
    public function load(ObjectManager $manager): void
    {
        $user = User::requestJoinByEmail(
            new Id('00000000-0000-0000-0000-000000000001'),
            $date = new DateTimeImmutable('-30 days'),
            new Email('user@app.test'),
            new PasswordHash(self::PASSWORD_HASH, new DateTimeImmutable('+1 day')),
            new Token($value = Uuid::uuid4()->toString(), new DateTimeImmutable('+1 day'))
        );

        $user->confirmJoin($value, $date);

        $manager->persist($user);

        $manager->flush();
    }
}
