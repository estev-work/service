<?php

namespace Project\Base\Infrastructure\Bus;

use Project\Base\Application\Bus\EventBusInterface;
use Project\Base\Application\Bus\MessageBrokerInterface;
use Project\Base\Domain\Events\EventInterface;
use Project\Common\EventHelper;

class EventBus implements EventBusInterface
{
    private MessageBrokerInterface $broker;

    public function __construct(MessageBrokerInterface $broker)
    {
        $this->broker = $broker;
    }

    public function publish(EventInterface $event): void
    {
        $topic = EventHelper::getTopicName($event::class);
        $message = [
            'eventName' => $event->getName(),
            'payload' => $event->getPayload(),
        ];
        $this->broker->publish($topic, $event->getKey(), json_encode($message));
    }

    public function subscribe(EventInterface $event): void
    {
        //TODO
    }
}
