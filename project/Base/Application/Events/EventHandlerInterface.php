<?php

namespace Project\Base\Application\Events;

interface EventHandlerInterface
{
    public function handle(EventDataInterface $event);
}