<?php

declare(strict_types=1);

namespace Project\Modules\Activities\Domain\ValueObjects;

use Exception;

final class ActivityContent
{
    private ?string $hash = null;

    private function __construct(private readonly string $value)
    {
    }

    /**
     * @throws Exception
     */
    public static function fromString(string $value): self
    {
        if ($value !== '') {
            return new self($value);
        }
        throw new Exception('Invalid activity title.');
    }

    private function getHash(): string
    {
        if ($this->hash === null) {
            $this->hash = md5($this->getOriginalText());
        }
        return $this->hash;
    }

    public function equals(ActivityContent $activityTitle): bool
    {
        if ($this->getHash() === $activityTitle->getHash()) {
            return true;
        }

        return false;
    }

    public function getText(): string
    {
        return strip_tags($this->value);
    }

    public function getOriginalText(): string
    {
        return $this->value;
    }
}
