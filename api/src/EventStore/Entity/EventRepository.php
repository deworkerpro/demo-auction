<?php

declare(strict_types=1);

namespace App\EventStore\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final readonly class EventRepository
{
    /**
     * @param EntityRepository<Event> $repo
     */
    public function __construct(
        private EntityManagerInterface $em,
        private EntityRepository $repo
    ) {}

    public function add(Event $event): void
    {
        $this->em->persist($event);
    }

    /**
     * @return Event[]
     */
    public function allSince(int $id): array
    {
        /** @var Event[] */
        return $this->repo->createQueryBuilder('t')
            ->select('t')
            ->andWhere('t.id > :id')
            ->setParameter(':id', $id)
            ->orderBy('t.id', 'ASC')
            ->getQuery()->getResult();
    }
}
