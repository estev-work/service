<?php

declare(strict_types=1);


namespace Project\Base\Infrastructure\Events;

use Project\Base\Application\Bus\EventBusInterface;
use Project\Base\Domain\AggregateRootInterface;

final class UnitOfWork
{
    private EventBusInterface $eventBus;
    private array $aggregates = [];

    public function __construct(EventBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function registerAggregateRoot(AggregateRootInterface $aggregate): void
    {
        $this->aggregates[] = $aggregate;
    }

    public function commit(): void
    {
        /** @var AggregateRootInterface $aggregate */
        foreach ($this->aggregates as $aggregate) {
            $events = $aggregate->pullEvents();
            foreach ($events as $event) {
                $this->eventBus->publish($event);
            }
        }
        $this->aggregates = [];
    }
}
