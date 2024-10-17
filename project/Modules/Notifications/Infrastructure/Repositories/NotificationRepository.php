<?php

namespace Project\Modules\Notifications\Infrastructure\Repositories;

use Exception;
use Project\Base\Infrastructure\Repositories\Mysql\MysqlRepository;
use Project\Common\Attributes\Table;
use Project\Modules\Notifications\Domain\Notification;
use Project\Modules\Notifications\Domain\Repositories\NotificationRepositoryInterface;

#[Table('mn_notifications')]
class NotificationRepository extends MysqlRepository implements NotificationRepositoryInterface
{
    /**
     * @throws Exception
     */
    public function saveNotification(Notification $notification): string|null
    {
        try {
            $data = [
                'id' => $notification->id()->getValue(),
                'title' => $notification->title()->getValue(),
                'body' => $notification->body()->getValue(),
                'created_at' => $notification->createdAt()->format('Y-m-d H:i:s'),
            ];
            $query
                = 'INSERT INTO notifications (id, title, body, created_at) VALUES(:id, :title, :body, :created_at) ON DUPLICATE KEY UPDATE id=:id, title=:title';
            $this->raw($query, $data);
            return $notification->id()->getValue();
        } catch (\Throwable $exception) {
            return null;
        }
    }

    public function findByNotificationId(string $id): ?Notification
    {
        throw new \RuntimeException('Not implemented');
    }

    public function findAllNotification(): array
    {
        throw new \RuntimeException('Not implemented');
    }
}
