<?php

namespace Project\Modules\Notifications\Domain;

use Exception;
use Project\Base\Domain\BaseAggregate;
use Project\Base\Domain\ValueObjects\DateValue;
use Project\Base\Domain\ValueObjects\Identifier;
use Project\Base\Domain\ValueObjects\Title;
use Project\Modules\Notifications\Domain\Events\NotificationCreatedEvent;
use Project\Modules\Notifications\Domain\ValueObjects\NotificationBody;

class Notification extends BaseAggregate
{
    private(set) readonly Identifier $id;
    private(set) Title $title;
    private(set) NotificationBody $body;
    private(set) readonly DateValue $createdAt;
    private(set) ?DateValue $updatedAt;

    private function __construct(
        Identifier $id,
        Title $title,
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
        ?string $updatedAt = null
    ): self {
        $activityId = Identifier::fromString($id);
        $activityTitle = Title::fromString($title);
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
        $activityId = Identifier::create();
        $activityTitle = Title::fromString($title);
        $activityBody = NotificationBody::fromString($body);
        $instance = new Notification($activityId, $activityTitle, $activityBody, DateValue::make(), null);
        $instance->recordEvent(
            new NotificationCreatedEvent(
                $instance
            )
        );
        return $instance;
    }

    /**
     * @throws Exception
     */
    public function changeTitle(string $title): void
    {
        $title = Title::fromString($title);
        if (!$this->title->equals($title)) {
            $this->title = $title;
            $this->updatedAt = DateValue::make();
        }
    }

    /**
     * @throws Exception
     */
    public function changeContent(string $content): void
    {
        $this->body = NotificationBody::fromString($content);
        $this->updatedAt = DateValue::make();
    }
}
