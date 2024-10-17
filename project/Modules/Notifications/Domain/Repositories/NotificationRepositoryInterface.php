<?php

namespace Project\Modules\Notifications\Domain\Repositories;

use Project\Modules\Notifications\Domain\Notification;

interface NotificationRepositoryInterface
{
    /**
     * @param Notification $notification
     *
     * @return string|null
     */
    public function saveNotification(Notification $notification): string|null;

    /**
     * @param string $id
     *
     * @return Notification|null
     */
    public function findByNotificationId(string $id): ?Notification;

    /**
     * @return array
     */
    public function findAllNotification(): array;
}
