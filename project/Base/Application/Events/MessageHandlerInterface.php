<?php

namespace Project\Base\Application\Events;

interface MessageHandlerInterface
{
    public function prepareMessage(string $key, string $payload, array $headers): EventDataInterface;

    /**
     * @param EventListenerInterface[] $listeners
     *
     * @return void
     */
    public function addListeners(array $listeners): void;
}