<?php

declare(strict_types=1);

namespace Project\Modules\Notifications\Application\Events\Handlers;

use Project\Base\Application\Events\EventDataInterface;
use Project\Base\Application\Events\EventHandlerInterface;
use Project\Common\Attributes\EventHandler;

#[EventHandler(eventName: 'activity-created')]
final class ActivityCreateEventHandler implements EventHandlerInterface
{

    public function handle(EventDataInterface $event)
    {
        // TODO: Implement handle() method.
    }
}