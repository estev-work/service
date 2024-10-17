<?php

namespace Project\Modules\Notifications\Domain;

use Exception;
use Project\Base\Domain\BaseAggregate;
use Project\Modules\Notifications\Domain\Events\NotificationCreatedEvent;
use Project\Modules\Notifications\Domain\ValueObjects\DateValue;
use Project\Modules\Notifications\Domain\ValueObjects\NotificationBody;
use Project\Modules\Notifications\Domain\ValueObjects\NotificationId;
use Project\Modules\Notifications\Domain\ValueObjects\NotificationTitle;

class Notification extends BaseAggregate
{
    private readonly NotificationId $id;
    private NotificationTitle $title;
    private NotificationBody $body;
    private readonly DateValue $createdAt;
    private ?DateValue $updatedAt;

    private function __construct(
        NotificationId $id,
        NotificationTitle $title,
        NotificationBody $body,
        DateValue $createdAt,
        ?DateValue $updatedAt
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->body = $body;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /**
     * @throws Exception
     */
    public static function from(
        string $id,
        string $title,
        string $body,
        string $createdAt,
        string $updatedAt = null
    ): self {
        $activityId = NotificationId::fromString($id);
        $activityTitle = NotificationTitle::fromString($title);
        $activityBody = NotificationBody::fromString($body);
        return new self(
            $activityId,
            $activityTitle,
            $activityBody,
            DateValue::fromString($createdAt),
            $updatedAt ? DateValue::fromString($updatedAt) : null
        );
    }

    /**
     * @throws Exception
     */
    public static function createNew(string $title, string $body): self
    {
        $activityId = NotificationId::generate();
        $activityTitle = NotificationTitle::fromString($title);
        $activityBody = NotificationBody::fromString($body);
        $instance = new Notification($activityId, $activityTitle, $activityBody, DateValue::make(), null);
        $instance->recordEvent(
            new NotificationCreatedEvent(
                $instance
            )
        );
        return $instance;
    }

    public function id(): NotificationId
    {
        return $this->id;
    }

    public function title(): NotificationTitle
    {
        return $this->title;
    }

    /**
     * @throws Exception
     */
    public function changeTitle(string $title): void
    {
        $title = NotificationTitle::fromString($title);
        if (!$this->title->equals($title)) {
            $this->title = $title;
            $this->updatedAt = DateValue::make();
        }
    }

    public function body(): NotificationBody
    {
        return $this->body;
    }

    /**
     * @throws Exception
     */
    public function changeContent(string $content): void
    {
        $this->body = NotificationBody::fromString($content);
        $this->updatedAt = DateValue::make();
    }

    public function createdAt(): DateValue
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?DateValue
    {
        return $this->updatedAt;
    }
}
