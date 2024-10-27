<?php

namespace Project\Base\Application\Bus;

use Project\Base\Domain\Events\EventInterface;

interface MessageBrokerInterface
{
    public function publish(EventInterface $event): void;

    public function subscribe(string $queueName, callable $callback): void;
}
