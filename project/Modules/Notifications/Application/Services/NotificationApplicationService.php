<?php

namespace Project\Modules\Notifications\Application\Services;

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
        //TODO проверки для функции и try catch
        $command = new CreateNotificationCommand(
            $data->title,
            $data->content
        );
        $this->commandBus->register(resolve(CreateNotificationHandler::class));
        /** @var Notification $activity */
        $activity = $this->commandBus->handle($command);
        return $activity->id()->getValue();
    }

    public function getListeners(): array
    {
        return [new NotificationListener($this)];
    }
}
