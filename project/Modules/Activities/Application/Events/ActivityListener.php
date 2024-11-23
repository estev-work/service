<?php

namespace Project\Modules\Activities\Application\Events;

use Project\Base\Application\Events\EventHandlerInterface;
use Project\Base\Application\Events\EventListenerInterface;
use Project\Modules\Activities\Application\Events\Handlers\ActivityCreateEventHandler;
use Project\Modules\Activities\Application\Services\ActivityApplicationService;

readonly class ActivityListener implements EventListenerInterface
{
    /** @var EventHandlerInterface[] */
    private array $events;
    private ActivityApplicationService $service;

    public function __construct(ActivityApplicationService $service)
    {
        $this->events['activity-created'] = new ActivityCreateEventHandler();
    }

    public function getEvent(string $eventName): EventHandlerInterface
    {
        return $this->events[$eventName];
    }

    public function hasEvents(string $eventName): bool
    {
        return isset($this->events[$eventName]);
    }
}
