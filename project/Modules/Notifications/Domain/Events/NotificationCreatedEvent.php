<?php

namespace Project\Modules\Notifications\Domain\Events;

use Project\Base\Domain\Events\EventInterface;
use Project\Modules\Notifications\Domain\Notification;

readonly class NotificationCreatedEvent implements EventInterface
{
    public string $activityId;
    public string $title;
    public string $content;
    public string $createdAt;

    public function __construct(
        Notification $notification,
    ) {
        $this->activityId = $notification->id()->getValue();
        $this->title = $notification->title()->getValue();
        $this->content = $notification->body()->getValue();
        $this->createdAt = $notification->createdAt()->format();
    }

    public function getName(): string
    {
        return "activity.created";
    }

    public function getPayload(): string
    {
        return serialize($this);
    }

    public function getKey(): string
    {
        return $this->activityId;
    }
}
