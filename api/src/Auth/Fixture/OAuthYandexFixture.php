<?php

declare(strict_types=1);

namespace App\Auth\Fixture;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\Network;
use App\Auth\Entity\User\Token;
use App\Auth\Entity\User\User;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Override;
use Ramsey\Uuid\Uuid;

final class OAuthYandexFixture extends AbstractFixture
{
    #[Override]
    public function load(ObjectManager $manager): void
    {
        $user = User::requestJoinByEmail(
            Id::generate(),
            $date = new DateTimeImmutable(),
            new Email('yandex-active@app.test'),
            'hash',
            new Token($value = Uuid::uuid4()->toString(), $date->modify('+1 day'))
        );

        $user->confirmJoin($value, $date);

        $manager->persist($user);

        $user = User::requestJoinByEmail(
            Id::generate(),
            $date = new DateTimeImmutable(),
            new Email('yandex-wait@app.test'),
            'hash',
            new Token(Uuid::uuid4()->toString(), $date->modify('+1 day'))
        );

        $manager->persist($user);

        $user = User::joinByNetwork(
            Id::generate(),
            new DateTimeImmutable(),
            new Email('yandex-network@app.test'),
            new Network('yandex', '33333333')
        );

        $manager->persist($user);

        $manager->flush();
    }
}
