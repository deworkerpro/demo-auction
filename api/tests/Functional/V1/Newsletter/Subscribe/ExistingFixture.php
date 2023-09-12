<?php

declare(strict_types=1);

namespace Test\Functional\V1\Newsletter\Subscribe;

use App\Auth\Entity\User\Id;
use App\Auth\Test\Builder\UserBuilder;
use App\Newsletter\Entity\Subscription\Email;
use App\Newsletter\Entity\Subscription\Id as SubscriptionId;
use App\Newsletter\Entity\Subscription\Subscription;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

final class ExistingFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $user = (new UserBuilder())
            ->withId(new Id('00000000-0000-0000-0000-000000000001'))
            ->active()
            ->build();

        $manager->persist($user);

        $subscription = new Subscription(
            new SubscriptionId($user->getId()->getValue()),
            new Email($user->getEmail()->getValue())
        );

        $manager->persist($subscription);

        $manager->flush();
    }
}
