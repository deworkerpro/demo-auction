<?php

declare(strict_types=1);

namespace Test\Functional\OAuth\Yandex;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Network;
use App\Auth\Test\Builder\UserBuilder;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Override;

final class CallbackFixture extends AbstractFixture
{
    #[Override]
    public function load(ObjectManager $manager): void
    {
        $user = (new UserBuilder())
            ->withEmail(new Email('yandex-active@app.test'))
            ->active()
            ->build();

        $manager->persist($user);

        $user = (new UserBuilder())
            ->withEmail(new Email('yandex-wait@app.test'))
            ->build();

        $manager->persist($user);

        $user = (new UserBuilder())
            ->withEmail(new Email('yandex-network@app.test'))
            ->viaNetwork(new Network('yandex', '33333333'))
            ->build();

        $manager->persist($user);

        $manager->flush();
    }
}
