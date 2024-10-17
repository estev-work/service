<?php

namespace Project\Modules\Activities\Domain;

use Project\Base\Domain\BaseAggregate;
use Project\Modules\Activities\Domain\Events\ActivityCreatedEvent;
use Project\Modules\Activities\Domain\ValueObjects\ActivityContent;
use Project\Modules\Activities\Domain\ValueObjects\ActivityId;
use Project\Modules\Activities\Domain\ValueObjects\ActivityTitle;
use Project\Modules\Activities\Domain\ValueObjects\DateValue;

class Activity extends BaseAggregate
{
    private readonly ActivityId $id;
    private ActivityTitle $title;
    private ActivityContent $content;
    private readonly DateValue $createdAt;
    private ?DateValue $updatedAt;

    private function __construct(
        ActivityId $id,
        ActivityTitle $title,
        ActivityContent $content,
        DateValue $createdAt,
        ?DateValue $updatedAt
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function from(
        string $id,
        string $title,
        string $content,
        string $createdAt,
        string $updatedAt = null
    ): self {
        $activityId = ActivityId::fromString($id);
        $activityTitle = ActivityTitle::fromString($title);
        $activityContent = ActivityContent::fromString($content);
        return new self(
            $activityId,
            $activityTitle,
            $activityContent,
            DateValue::fromString($createdAt),
            $updatedAt ? DateValue::fromString($updatedAt) : null
        );
    }

    public static function createNew(string $title, string $content): self
    {
        $activityId = ActivityId::generate();
        $activityTitle = ActivityTitle::fromString($title);
        $activityContent = ActivityContent::fromString($content);
        $instance = new Activity($activityId, $activityTitle, $activityContent, DateValue::make(), null);
        $instance->recordEvent(
            new ActivityCreatedEvent(
                $instance
            )
        );
        return $instance;
    }

    public function id(): ActivityId
    {
        return $this->id;
    }

    public function title(): ActivityTitle
    {
        return $this->title;
    }

    public function changeTitle(string $title): void
    {
        $this->title = ActivityTitle::fromString($title);
        $this->updatedAt = DateValue::make();
    }

    public function content(): ActivityContent
    {
        return $this->content;
    }

    public function changeContent(string $content): void
    {
        $this->content = ActivityContent::fromString($content);
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
