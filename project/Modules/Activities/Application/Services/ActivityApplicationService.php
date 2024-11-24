<?php

namespace Project\Modules\Activities\Application\Services;

use Exception;
use Project\Base\Application\Bus\CommandBusInterface;
use Project\Modules\Activities\Api\ActivityApiInterface;
use Project\Modules\Activities\Api\DTO\CreateActivityDTO;
use Project\Modules\Activities\Application\Commands\CreateActivity\CreateActivityCommand;
use Project\Modules\Activities\Application\Commands\CreateActivity\CreateActivityHandler;
use Project\Modules\Activities\Domain\Activity;

readonly class ActivityApplicationService implements ActivityApiInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
    ) {
    }

    /**
     * @throws Exception
     */
    public function createActivity(CreateActivityDTO $data): string
    {
        $command = new CreateActivityCommand(
            $data->title,
            $data->content
        );
        $this->commandBus->register(resolve(CreateActivityHandler::class));
        /** @var Activity $activity */
        $activity = $this->commandBus->handle($command);
        return $activity->id->toString();
    }

    public function getListeners(): array
    {
        return [];
    }
}
