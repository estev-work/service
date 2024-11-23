<?php

namespace Project\Modules\Notifications\Application\Events;

use Core\Logger\AppLoggerInterface;
use Project\Base\Application\Events\EventHandlerInterface;
use Project\Base\Application\Events\EventListenerInterface;
use Project\Modules\Notifications\Api\NotificationApiInterface;
use Project\Modules\Notifications\Application\Events\Handlers\ActivityCreateEventHandler;
use Project\Modules\Notifications\Application\Services\NotificationApplicationService;

final class NotificationListener implements EventListenerInterface
{
    /** @var EventHandlerInterface[] */
    private array $events;
    private readonly NotificationApplicationService $service;
    private readonly AppLoggerInterface $logger;

    public function __construct(NotificationApiInterface $service, AppLoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->logger->setChannel('notifications');
        $this->events['activity.created'] = new ActivityCreateEventHandler($logger);
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
