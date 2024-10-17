<?php

namespace Project\Modules\Activities\Domain\Events;

use Project\Base\Domain\Events\EventInterface;
use Project\Modules\Activities\Domain\Activity;

readonly class ActivityCreatedEvent implements EventInterface
{
    public string $activityId;
    public string $title;
    public string $content;
    public string $createdAt;

    public function __construct(
        Activity $activity,
    ) {
        $this->activityId = $activity->id()->getValue();
        $this->title = $activity->title()->getOriginalText();
        $this->content = $activity->content()->getOriginalText();
        $this->createdAt = $activity->createdAt()->format();
    }

    public function getName(): string
    {
        return "activity.created";
    }

    public function getPayload(): array
    {
        return [
            "activityId" => $this->activityId,
            "title" => $this->title,
            "content" => $this->content,
            "createdAt" => $this->createdAt
        ];
    }

    public function getKey(): string
    {
        return $this->activityId;
    }
}
