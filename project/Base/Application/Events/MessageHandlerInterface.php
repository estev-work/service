<?php

namespace Project\Base\Application\Events;

interface MessageHandlerInterface
{
    public function handleMessage(string $eventName, array $payload = [], array $headers = []): bool;

    /**
     * @param EventListenerInterface[] $listeners
     *
     * @return void
     */
    public function addListeners(array $listeners): void;
}