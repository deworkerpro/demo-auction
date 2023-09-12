<?php

declare(strict_types=1);

namespace App\Newsletter\Listener;

use App\Auth\Event\UserSignedUp;
use App\Newsletter\Command\Subscribe\Command;
use App\Newsletter\Command\Subscribe\Handler;
use App\Validator\Validator;

final readonly class SubscribeToNewsletterByUserSignedUp
{
    public function __construct(
        private Validator $validator,
        private Handler $handler
    ) {}

    public function __invoke(UserSignedUp $event): void
    {
        $command = new Command(
            $event->id,
            $event->email,
        );

        $this->validator->validate($command);

        $this->handler->handle($command);
    }
}
