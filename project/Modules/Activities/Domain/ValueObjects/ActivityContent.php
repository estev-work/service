<?php

declare(strict_types=1);

namespace Project\Modules\Activities\Domain\ValueObjects;

use Project\Base\Domain\Exceptions\DomainLayerException;

use function PHPUnit\Framework\isEmpty;

final class ActivityContent
{
    private ?string $hash = null {
        get => isEmpty($this->hash) ? $this->hash = md5($this->value) : $this->hash;
    }

    private function __construct(private(set) readonly string $value)
    {
    }

    public static function fromString(string $value): self
    {
        if ($value !== '') {
            return new ActivityContent($value);
        }
        throw new DomainLayerException('Invalid activity title.');
    }

    public function equals(ActivityContent $activityTitle): bool
    {
        if ($this->hash === $activityTitle->hash) {
            return true;
        }

        return false;
    }

    public function getText(): string
    {
        return strip_tags($this->value);
    }
}
