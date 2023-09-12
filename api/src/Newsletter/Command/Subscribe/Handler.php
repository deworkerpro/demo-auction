<?php

declare(strict_types=1);

namespace App\Newsletter\Command\Subscribe;

use App\Flusher;
use App\Newsletter\Entity\Subscription\Email;
use App\Newsletter\Entity\Subscription\Id;
use App\Newsletter\Entity\Subscription\Subscription;
use App\Newsletter\Entity\Subscription\SubscriptionRepository;
use DomainException;

final readonly class Handler
{
    public function __construct(
        private SubscriptionRepository $subscriptions,
        private Flusher $flusher
    ) {}

    public function handle(Command $command): void
    {
        $id = new Id($command->id);

        if ($this->subscriptions->has($id)) {
            throw new DomainException('Subscription already exists.');
        }

        $subscription = new Subscription(
            $id,
            new Email($command->email)
        );

        $this->subscriptions->add($subscription);

        $this->flusher->flush();
    }
}
