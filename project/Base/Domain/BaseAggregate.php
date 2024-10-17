<?php


namespace Project\Base\Domain;

use Project\Base\Domain\Events\EventInterface;

abstract class BaseAggregate implements AggregateRootInterface
{
    protected array $events = [];

    protected function recordEvent(EventInterface $event): void
    {
        $this->events[] = $event;
    }

    public function pullEvents(): array
    {
        return $this->events;
    }
}
