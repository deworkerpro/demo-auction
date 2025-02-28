<?php

declare(strict_types=1);

namespace Test\Functional\OAuth\MailRu;

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
        $user = new UserBuilder()
            ->withEmail(new Email('active@app.test'))
            ->active()
            ->build();

        $manager->persist($user);

        $user = new UserBuilder()
            ->withEmail(new Email('wait@app.test'))
            ->build();

        $manager->persist($user);

        $user = new UserBuilder()
            ->withEmail(new Email('network@app.test'))
            ->viaNetwork(new Network('mailru', '0000001'))
            ->build();

        $manager->persist($user);

        $manager->flush();
    }
}
