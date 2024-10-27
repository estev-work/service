<?php

namespace Project\Modules\Notifications\Application\Services;

use Core\Logger\AppLoggerInterface;
use Exception;
use Project\Base\Application\Bus\CommandBusInterface;
use Project\Modules\Notifications\Api\DTO\CreateNotificationDTO;
use Project\Modules\Notifications\Api\NotificationApiInterface;
use Project\Modules\Notifications\Application\Commands\CreateNotification\CreateNotificationCommand;
use Project\Modules\Notifications\Application\Commands\CreateNotification\CreateNotificationHandler;
use Project\Modules\Notifications\Application\Events\NotificationListener;
use Project\Modules\Notifications\Domain\Notification;

readonly class NotificationApplicationService implements NotificationApiInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
    ) {
    }

    /**
     * @throws Exception
     */
    public function createNotification(CreateNotificationDTO $data): string
    {
        $command = new CreateNotificationCommand(
            $data->title,
            $data->content
        );
        /** @var CreateNotificationHandler $handler */
        $handler = resolve(CreateNotificationHandler::class);
        $this->commandBus->register($handler);
        /** @var Notification $activity */
        $activity = $this->commandBus->handle($command);
        return $activity->id()->getValue();
    }

    /**
     * @throws Exception
     */
    public function getListeners(): array
    {
        return [new NotificationListener($this, resolve(AppLoggerInterface::class))];
    }
}
