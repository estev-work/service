<?php

namespace Project\Modules\Notifications\Application\Queries\GetNotificationById;

use Project\Base\Application\Queries\QueryHandlerInterface;
use Project\Base\Application\Queries\QueryInterface;
use Project\Modules\Notifications\Domain\Notification;
use Project\Modules\Notifications\Domain\Repositories\NotificationRepositoryInterface;

class GetNotificationByIdHandler implements QueryHandlerInterface
{
    private NotificationRepositoryInterface $repository;

    public function __construct(NotificationRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(GetNotificationByIdQuery|QueryInterface $query): ?Notification
    {
        return $this->repository->findByNotificationId($query->getId());
    }
}
