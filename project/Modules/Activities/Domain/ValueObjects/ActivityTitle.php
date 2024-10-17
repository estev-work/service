<?php

declare(strict_types=1);

namespace Project\Modules\Activities\Domain\ValueObjects;


use Exception;

final readonly class ActivityTitle
{
    private function __construct(private string $value)
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

    public function equals(ActivityTitle $activityTitle): bool
    {
        if (strlen($this->getOriginalText()) === strlen($activityTitle->getOriginalText())
            && $this->getOriginalText() === $activityTitle->getOriginalText()
        ) {
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
