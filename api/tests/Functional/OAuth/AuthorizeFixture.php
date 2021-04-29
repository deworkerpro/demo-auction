<?php

declare(strict_types=1);

namespace Test\Functional\OAuth;

use App\Auth\Entity\User\Email;
use App\Auth\Test\Builder\UserBuilder;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

final class AuthorizeFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $user = (new UserBuilder())
            ->withEmail(new Email('active@app.test'))
            ->withPasswordHash('$2y$12$qwnND33o8DGWvFoepotSju7eTAQ6gzLD/zy6W8NCVtiHPbkybz.w6') // 'password'
            ->active()
            ->build();
        $manager->persist($user);

        $user = (new UserBuilder())
            ->withEmail(new Email('wait@app.test'))
            ->withPasswordHash('$2y$12$qwnND33o8DGWvFoepotSju7eTAQ6gzLD/zy6W8NCVtiHPbkybz.w6') // 'password'
            ->build();
        $manager->persist($user);

        $manager->flush();
    }
}
