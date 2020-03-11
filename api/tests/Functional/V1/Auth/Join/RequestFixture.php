<?php

declare(strict_types=1);

namespace Test\Functional\V1\Auth\Join;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\Token;
use App\Auth\Entity\User\User;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class RequestFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $user = User::requestJoinByEmail(
            Id::generate(),
            $date = new DateTimeImmutable('-30 days'),
            new Email('existing@app.test'),
            'password-hash',
            new Token($value = Uuid::uuid4()->toString(), $date->modify('+1 day'))
        );

        $manager->persist($user);

        $manager->flush();
    }
}
