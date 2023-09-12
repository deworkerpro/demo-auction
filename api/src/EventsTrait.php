<?php

declare(strict_types=1);

namespace App;

trait EventsTrait
{
    /**
     * @var object[]
     */
    private array $recordedEvents = [];

    /**
     * @return object[]
     */
    public function releaseEvents(): array
    {
        $events = $this->recordedEvents;
        $this->recordedEvents = [];
        return $events;
    }

    protected function recordEvent(object $event): void
    {
        $this->recordedEvents[] = $event;
    }
}
