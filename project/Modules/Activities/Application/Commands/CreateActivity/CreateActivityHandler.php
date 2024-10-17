<?php

namespace Project\Modules\Activities\Application\Commands\CreateActivity;

use Exception;
use Project\Base\Application\Commands\AbstractCommandHandler;
use Project\Base\Application\Commands\CommandHandlerInterface;
use Project\Base\Application\Commands\CommandInterface;
use Project\Base\Application\Events\UnitOfWorkInterface;
use Project\Common\Attributes\Command;
use Project\Modules\Activities\Domain\Activity;
use Project\Modules\Activities\Domain\Repositories\ActivityRepositoryInterface;

#[Command(command: CreateActivityCommand::class)]
class CreateActivityHandler extends AbstractCommandHandler implements CommandHandlerInterface
{
    private ActivityRepositoryInterface $activityRepository;
    private UnitOfWorkInterface $unitOfWork;

    public function __construct(
        ActivityRepositoryInterface $repository,
        UnitOfWorkInterface $unitOfWork,
    ) {
        $this->activityRepository = $repository;
        $this->unitOfWork = $unitOfWork;
    }

    /** @param CreateActivityCommand $command
     * @throws Exception
     */
    public function execute(CommandInterface $command): Activity
    {
        $this->assertCorrectCommand($command, CreateActivityCommand::class);
        $activity = Activity::createNew($command->getTitle(), $command->getContent());
        $this->unitOfWork->registerAggregateRoot($activity);
        $this->activityRepository->saveActivity($activity);
        $this->unitOfWork->commit();
        return $activity;
    }
}
