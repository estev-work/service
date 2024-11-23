<?php

namespace Project\Base\Infrastructure\Bus;

use Project\Base\Application\Bus\EventBusInterface;
use Project\Base\Application\Bus\MessageBrokerInterface;
use Project\Base\Domain\Events\EventInterface;

class EventBus implements EventBusInterface
{
    private MessageBrokerInterface $broker;

    public function __construct(MessageBrokerInterface $broker)
    {
        $this->broker = $broker;
    }

    public function publish(EventInterface $event): void
    {
        $this->broker->publish($event);
    }

    public function subscribe(EventInterface $event): void
    {
        //TODO
    }
}
