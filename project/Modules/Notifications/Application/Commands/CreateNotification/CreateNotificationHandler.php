<?php

namespace Project\Modules\Notifications\Application\Commands\CreateNotification;

use Exception;
use Project\Base\Application\Commands\AbstractCommandHandler;
use Project\Base\Application\Commands\CommandHandlerInterface;
use Project\Base\Application\Commands\CommandInterface;
use Project\Base\Application\Events\UnitOfWorkInterface;
use Project\Common\Attributes\Command;
use Project\Modules\Notifications\Domain\Notification;
use Project\Modules\Notifications\Domain\Repositories\NotificationRepositoryInterface;

#[Command(command: CreateNotificationCommand::class)]
class CreateNotificationHandler extends AbstractCommandHandler implements CommandHandlerInterface
{
    private NotificationRepositoryInterface $activityRepository;
    private UnitOfWorkInterface $unitOfWork;

    public function __construct(
        NotificationRepositoryInterface $repository,
        UnitOfWorkInterface $unitOfWork,
    ) {
        $this->activityRepository = $repository;
        $this->unitOfWork = $unitOfWork;
    }

    /** @param CreateNotificationCommand $command
     * @throws Exception
     */
    public function execute(CommandInterface $command): Notification
    {
        $this->assertCorrectCommand($command, CreateNotificationCommand::class);
        $activity = Notification::createNew($command->getTitle(), $command->getContent());
        $this->unitOfWork->registerAggregateRoot($activity);
        $this->activityRepository->saveNotification($activity);
        $this->unitOfWork->commit();
        return $activity;
    }
}
