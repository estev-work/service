<?php

namespace Project\Base\Application\Bus;

use Project\Base\Domain\Events\EventInterface;

interface EventBusInterface
{
    public function publish(EventInterface $event): void;

    public function subscribe(EventInterface $event): void;
}
