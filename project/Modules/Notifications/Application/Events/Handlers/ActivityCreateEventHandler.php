<?php

declare(strict_types=1);

namespace Project\Modules\Notifications\Application\Events\Handlers;

use Core\Logger\AppLoggerInterface;
use Project\Base\Application\Events\EventHandlerInterface;
use Project\Base\Application\Events\MessageInterface;

final readonly class ActivityCreateEventHandler implements EventHandlerInterface
{
    public function __construct(private AppLoggerInterface $logger)
    {
        $this->logger->setChannel('notifications');
    }

    public function handle(MessageInterface $event): void
    {
        $this->logger->debug('Message handle ActivityCreateEventHandler', ['message' => [
            'key'=>$event->getKey(),
            'name'=> $event->getName(),
            'payload'=> $event->getPayload(),
        ]]);
    }
}