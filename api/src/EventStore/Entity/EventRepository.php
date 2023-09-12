<?php

declare(strict_types=1);

namespace App\EventStore\Entity;

use Doctrine\ORM\EntityManagerInterface;

final readonly class EventRepository
{
    public function __construct(private EntityManagerInterface $em) {}

    public function add(Event $event): void
    {
        $this->em->persist($event);
    }
}
