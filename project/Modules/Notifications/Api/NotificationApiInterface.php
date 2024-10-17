<?php

namespace Project\Modules\Notifications\Api;

use Project\Base\Application\ListenerApiInterface;
use Project\Modules\Notifications\Api\DTO\CreateNotificationDTO;

interface NotificationApiInterface extends ListenerApiInterface
{
    public function createNotification(CreateNotificationDTO $data): string;
}
