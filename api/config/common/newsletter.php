<?php

declare(strict_types=1);

use App\Newsletter\Entity\Subscription\Subscription;
use App\Newsletter\Entity\Subscription\SubscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

return [
    SubscriptionRepository::class => static function (ContainerInterface $container): SubscriptionRepository {
        $em = $container->get(EntityManagerInterface::class);
        $repo = $em->getRepository(Subscription::class);
        return new SubscriptionRepository($em, $repo);
    },
];
