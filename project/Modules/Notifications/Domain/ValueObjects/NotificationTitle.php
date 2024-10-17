<?php

declare(strict_types=1);

namespace Project\Modules\Notifications\Domain\ValueObjects;

use Exception;

final readonly class NotificationTitle
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
        throw new Exception('Invalid notification title.');
    }

    public function equals(NotificationTitle $activityTitle): bool
    {
        if (strlen($this->getValue()) === strlen($activityTitle->getValue())
            && $this->getValue() === $activityTitle->getValue()
        ) {
            return true;
        }
        return false;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
