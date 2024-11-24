<?php

declare(strict_types=1);

namespace Tests\Modules\Activities\Domain;

use Exception;
use PHPUnit\Framework\TestCase;
use Project\Modules\Activities\Domain\Activity;
use Project\Modules\Activities\Domain\Events\ActivityCreatedEvent;

class ActivityTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testCreateNewActivity(): void
    {
        $title = 'New Activity';
        $content = 'This is a test activity.';
        $activity = Activity::createNew($title, $content);

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertSame($title, $activity->title->value);
        $this->assertSame($content, $activity->content->value);
        $this->assertNotNull($activity->createdAt);
        $this->assertNull($activity->updatedAt);

        $events = $activity->pullEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(ActivityCreatedEvent::class, $events[0]);
    }

    /**
     * @throws Exception
     */
    public function testChangeActivityTitle(): void
    {
        $activity = Activity::createNew('Old Title', 'Content');
        $newTitle = 'Updated Title';

        $activity->changeTitle($newTitle);

        $this->assertSame($newTitle, $activity->title->value);
        $this->assertNotNull($activity->updatedAt);
        $this->assertTrue($activity->updatedAt->isAfter($activity->createdAt->getDateImmutable()));
    }

    /**
     * @throws Exception
     */
    public function testChangeActivityContent(): void
    {
        $activity = Activity::createNew('Title', 'Old Content');
        $newContent = 'Updated Content';

        $activity->changeContent($newContent);

        $this->assertSame($newContent, $activity->content->value);
        $this->assertNotNull($activity->updatedAt);
    }

    /**
     * @throws Exception
     */
    public function testCreateFromExistingData(): void
    {
        $id = '123e4567-e89b-12d3-a456-426614174000';
        $title = 'Existing Title';
        $content = 'Existing Content';
        $createdAt = '2023-11-22T10:00:00+00:00';
        $updatedAt = '2023-11-22T12:00:00+00:00';

        $activity = Activity::from($id, $title, $content, $createdAt, $updatedAt);

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertSame($id, $activity->id->toString());
        $this->assertSame($title, $activity->title->value);
        $this->assertSame($content, $activity->content->value);
        $this->assertSame($createdAt, $activity->createdAt->format());
        $this->assertSame($updatedAt, $activity->updatedAt->format());
    }
}
