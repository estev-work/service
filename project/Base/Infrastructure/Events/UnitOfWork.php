<?php

declare(strict_types=1);

namespace Project\Base\Infrastructure\Events;

use Project\Base\Application\Bus\EventBusInterface;
use Project\Base\Application\Events\UnitOfWorkInterface;
use Project\Base\Domain\AggregateRootInterface;
use Project\Base\Domain\Events\EventInterface;

final class UnitOfWork implements UnitOfWorkInterface
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
            /** @var EventInterface $event */
            foreach ($events as $event) {
                $this->eventBus->publish($event);
            }
        }
        $this->aggregates = [];
    }
}
