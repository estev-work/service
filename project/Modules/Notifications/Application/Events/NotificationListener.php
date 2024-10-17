<?php

namespace Project\Modules\Notifications\Application\Events;

use Core\Logger\AppLoggerInterface;
use Project\Base\Application\Events\EventHandlerInterface;
use Project\Base\Application\Events\EventListenerInterface;
use Project\Common\Attributes\Command;
use Project\Modules\Notifications\Api\NotificationApiInterface;
use Project\Modules\Notifications\Application\Events\Handlers\ActivityCreateEventHandler;
use Project\Modules\Notifications\Application\Services\NotificationApplicationService;
use ReflectionClass;

readonly class NotificationListener implements EventListenerInterface
{
    /** @var EventHandlerInterface[] */
    private array $events;
    private NotificationApplicationService $service;
    private AppLoggerInterface $logger;

    public function __construct(NotificationApiInterface $service, AppLoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->logger->setChannel('notifications');
        $this->events['activity-created'] = new ActivityCreateEventHandler();
    }

    private function registerEventHandler(EventHandlerInterface $eventHandler): void
    {
        $reflectionClass = new ReflectionClass($eventHandler);

        $attributes = $reflectionClass->getAttributes(Command::class);

        if (!empty($attributes)) {
            try {
                $attribute = $attributes[0]->getArguments();
                $eventName = $attribute["eventName"];
            } catch (\Exception $exception) {
                $this->logger->error("Command handler for command {$commandClass} not found.");
            }
        }
        throw new \Exception("Handler not has command.");
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
