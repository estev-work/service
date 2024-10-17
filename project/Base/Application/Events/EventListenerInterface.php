<?php

namespace Project\Base\Application\Events;

interface EventListenerInterface
{
    /**
     * @param string $eventName
     *
     * @return EventHandlerInterface
     */
    public function getEvent(string $eventName): EventHandlerInterface;

    /**
     * @return bool
     */
    public function hasEvents(string $eventName): bool;
}
