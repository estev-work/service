<?php

declare(strict_types=1);

namespace Tests\Modules\Activities\Domain;

use PHPUnit\Framework\TestCase;
use Project\Base\Domain\ValueObjects\Title;
use Project\Modules\Activities\Domain\ValueObjects\ActivityContent;

class ValueObjectsTest extends TestCase
{
    public function testActivityContent(): void
    {
        $content = ActivityContent::fromString('Test content');
        $this->assertSame('Test content', $content->value);
        $this->assertSame('Test content', $content->getText());

        $sameContent = ActivityContent::fromString('Test content');
        $this->assertTrue($content->equals($sameContent));

        $differentContent = ActivityContent::fromString('Different content');
        $this->assertFalse($content->equals($differentContent));
    }

    public function testActivityTitle(): void
    {
        $title = Title::fromString('Test Title');
        $this->assertSame('Test Title', $title->value);

        $sameTitle = Title::fromString('Test Title');
        $this->assertTrue($title->equals($sameTitle));

        $differentTitle = Title::fromString('Different Title');
        $this->assertFalse($title->equals($differentTitle));

        $this->expectException(\Exception::class);
        Title::fromString('');
    }
}