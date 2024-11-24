<?php

declare(strict_types=1);

namespace Project\Base\Domain\ValueObjects;

use Project\Base\Domain\Exceptions\DomainLayerException;

readonly class Title
{
    private function __construct(private(set) string $value)
    {
    }

    /**
     * @throws DomainLayerException
     */
    public static function fromString(string $value): self
    {
        if ($value !== '') {
            return new self($value);
        }
        throw new DomainLayerException('Invalid notification title.');
    }

    public function equals(Title $activityTitle): bool
    {
        if (strlen($this->value) === strlen($activityTitle->value)
            && $this->value === $activityTitle->value
        ) {
            return true;
        }
        return false;
    }

    public function getText(): string
    {
        return strip_tags($this->value);
    }
}
