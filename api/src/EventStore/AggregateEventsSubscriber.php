<?php

declare(strict_types=1);

namespace App\EventStore;

use App\AggregateRoot;
use App\EventStore\Entity\Event;
use App\EventStore\Entity\EventRepository;
use App\EventStore\EventNameResolver\EventNameResolver;
use DateTimeImmutable;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Events;
use Psr\Container\ContainerInterface;

final readonly class AggregateEventsSubscriber implements EventSubscriber
{
    public function __construct(
        private ContainerInterface $container,
        private EventNameResolver $nameResolver,
        private EventSerializer $serializer
    ) {}

    public function getSubscribedEvents(): array
    {
        return [
            Events::preFlush => 'preFlush',
            Events::postFlush => 'postFlush',
        ];
    }

    public function preFlush(PreFlushEventArgs $args): void
    {
        $repository = $this->container->get(EventRepository::class);

        /** @var object[] $entities */
        foreach ($args->getObjectManager()->getUnitOfWork()->getIdentityMap() as $entities) {
            foreach ($entities as $entity) {
                if (!$entity instanceof AggregateRoot) {
                    continue;
                }

                foreach ($entity->releaseEvents() as $object) {
                    $repository->add(new Event(
                        date: new DateTimeImmutable(),
                        type: $this->nameResolver->nameForClass($object::class),
                        payload: $this->serializer->serialize($object),
                    ));
                }
            }
        }
    }

    public function postFlush(PostFlushEventArgs $args): void
    {
        $emitter = $this->container->get(EventsEmitter::class);
        $emitter->emitNewEvents();
    }
}
