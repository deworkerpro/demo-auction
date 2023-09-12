<?php

declare(strict_types=1);

namespace App\Newsletter\Command\Unsubscribe;

use App\Flusher;
use App\Newsletter\Entity\Subscription\Id;
use App\Newsletter\Entity\Subscription\SubscriptionRepository;

final readonly class Handler
{
    public function __construct(
        private SubscriptionRepository $subscriptions,
        private Flusher $flusher
    ) {}

    public function handle(Command $command): void
    {
        $subscription = $this->subscriptions->get(new Id($command->id));

        $this->subscriptions->remove($subscription);

        $this->flusher->flush();
    }
}
