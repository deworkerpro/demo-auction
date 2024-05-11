<?php

declare(strict_types=1);

namespace Test\Functional\OAuth;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\PasswordHash;
use App\Auth\Test\Builder\UserBuilder;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Override;

final class AuthorizeFixture extends AbstractFixture
{
    #[Override]
    public function load(ObjectManager $manager): void
    {
        $user = (new UserBuilder())
            ->withEmail(new Email('active@app.test'))
            ->withPasswordHash(new PasswordHash(
                '$2y$12$qwnND33o8DGWvFoepotSju7eTAQ6gzLD/zy6W8NCVtiHPbkybz.w6', // 'password'
                new DateTimeImmutable('+1 day')
            ))
            ->active()
            ->build();
        $manager->persist($user);

        $user = (new UserBuilder())
            ->withEmail(new Email('wait@app.test'))
            ->withPasswordHash(new PasswordHash(
                '$2y$12$qwnND33o8DGWvFoepotSju7eTAQ6gzLD/zy6W8NCVtiHPbkybz.w6', // 'password'
                new DateTimeImmutable('+1 day')
            ))
            ->build();
        $manager->persist($user);

        $user = (new UserBuilder())
            ->withEmail(new Email('expires@app.test'))
            ->withPasswordHash(new PasswordHash(
                '$2y$12$qwnND33o8DGWvFoepotSju7eTAQ6gzLD/zy6W8NCVtiHPbkybz.w6', // 'password'
                new DateTimeImmutable('-1 day')
            ))
            ->active()
            ->build();
        $manager->persist($user);

        $manager->flush();
    }
}
