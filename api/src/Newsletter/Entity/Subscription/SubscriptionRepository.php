<?php

declare(strict_types=1);

namespace App\Newsletter\Entity\Subscription;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use DomainException;

final class SubscriptionRepository
{
    /**
     * @param EntityRepository<Subscription> $repo
     */
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly EntityRepository $repo
    ) {}

    public function has(Id $id): bool
    {
        return $this->repo->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->andWhere('t.id = :id')
            ->setParameter(':id', $id->getValue())
            ->getQuery()->getSingleScalarResult() > 0;
    }

    public function get(Id $id): Subscription
    {
        $subscription = $this->repo->find($id->getValue());

        if ($subscription === null) {
            throw new DomainException('Subscription is not found.');
        }

        return $subscription;
    }

    public function add(Subscription $subscription): void
    {
        $this->em->persist($subscription);
    }

    public function remove(Subscription $subscription): void
    {
        $this->em->persist($subscription);
    }
}
