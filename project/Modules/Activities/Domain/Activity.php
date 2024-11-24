<?php

declare(strict_types=1);

namespace Project\Modules\Activities\Domain;

use Exception;
use Project\Base\Domain\BaseAggregate;
use Project\Base\Domain\ValueObjects\DateValue;
use Project\Base\Domain\ValueObjects\Identifier;
use Project\Base\Domain\ValueObjects\Title;
use Project\Modules\Activities\Domain\Events\ActivityCreatedEvent;
use Project\Modules\Activities\Domain\ValueObjects\ActivityContent;

final class Activity extends BaseAggregate
{
    private(set) Identifier $id;
    private(set) Title $title;
    private(set) ActivityContent $content;
    private(set) DateValue $createdAt;
    private(set) ?DateValue $updatedAt;

    private function __construct(
        Identifier $id,
        Title $title,
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

    /**
     * @throws Exception
     */
    public static function from(
        string $id,
        string $title,
        string $content,
        string $createdAt,
        ?string $updatedAt = null
    ): self {
        $activityId = Identifier::fromString($id);
        $activityTitle = Title::fromString($title);
        $activityContent = ActivityContent::fromString($content);
        return new Activity(
            id: $activityId,
            title: $activityTitle,
            content: $activityContent,
            createdAt: DateValue::fromString($createdAt),
            updatedAt: $updatedAt ? DateValue::fromString($updatedAt) : null
        );
    }

    public static function createNew(string $title, string $content): self
    {
        $activityId = Identifier::create();
        $activityTitle = Title::fromString($title);
        $activityContent = ActivityContent::fromString($content);
        $instance = new Activity($activityId, $activityTitle, $activityContent, DateValue::make(), null);
        $instance->recordEvent(
            new ActivityCreatedEvent(
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
        $this->title = Title::fromString($title);
        $this->updatedAt = DateValue::make();
    }

    /**
     * @throws Exception
     */
    public function changeContent(string $content): void
    {
        $this->content = ActivityContent::fromString($content);
        $this->updatedAt = DateValue::make();
    }
}
