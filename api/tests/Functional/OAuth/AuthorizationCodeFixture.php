<?php

declare(strict_types=1);

namespace Test\Functional\OAuth;

use App\Auth\Entity\User\Id;
use App\Auth\Test\Builder\UserBuilder;
use App\OAuth\Entity\AuthCode;
use App\OAuth\Entity\Client;
use App\OAuth\Entity\Scope;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

final class AuthorizationCodeFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $user = (new UserBuilder())
            ->withId(new Id('00000000-0000-0000-0000-000000000001'))
            ->active()
            ->build();

        $manager->persist($user);

        $code = new AuthCode();
        $code->setClient(new Client(
            identifier: 'frontend',
            name: 'Frontend',
            redirectUri: 'http://localhost/oauth',
        ));
        $code->addScope(new Scope('common'));
        $code->setExpiryDateTime(new DateTimeImmutable('2300-12-31 21:00:10'));
        $code->setIdentifier('def50200f204dedbb244ce4539b9e');
        $code->setUserIdentifier($user->getId()->getValue());
        $code->setRedirectUri('http://localhost/oauth');

        $manager->persist($code);

        $manager->flush();
    }
}
