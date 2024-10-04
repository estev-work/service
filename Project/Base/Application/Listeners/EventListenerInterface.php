<?php

namespace Project\Base\Application\Listeners;

use Project\Base\Domain\Events\EventInterface;

interface EventListenerInterface
{
    public function handle(EventInterface $event): bool;
}
