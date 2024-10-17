<?php

declare(strict_types=1);

namespace Project\Modules\Notifications\Domain\ValueObjects;

use Exception;

final class NotificationBody
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
            $this->hash = md5($this->getValue());
        }
        return $this->hash;
    }

    public function equals(NotificationBody $activityTitle): bool
    {
        if ($this->getHash() === $activityTitle->getHash()) {
            return true;
        }

        return false;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
