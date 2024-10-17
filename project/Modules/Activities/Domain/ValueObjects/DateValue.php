<?php

declare(strict_types=1);

namespace Project\Modules\Activities\Domain\ValueObjects;

final readonly class DateValue
{

    private function __construct(private \DateTimeInterface $value)
    {
    }

    public static function make(): self
    {
        $date = new \DateTimeImmutable();
        return new self($date);
    }

    public static function fromString(string $value): self
    {
        try {
            $date = new \DateTimeImmutable($value);
            return new self($date);
        } catch (\Throwable $e) {
            throw new \InvalidArgumentException('Invalid activity date format.');
        }
    }

    public static function fromDate(\DateTimeInterface $value): self
    {
        return new self($value);
    }

    public function isBefore(\DateTimeInterface $date): bool
    {
        return $this->value < $date;
    }

    public function isAfter(\DateTimeInterface $date): bool
    {
        return $this->value > $date;
    }

    public function equals(\DateTimeInterface $date): bool
    {
        return $this->value == $date;
    }

    public function isBetween(\DateTimeInterface $startDate, \DateTimeInterface $endDate): bool
    {
        return $this->value >= $startDate && $this->value <= $endDate;
    }

    public function isSameDay(\DateTimeInterface $date): bool
    {
        return $this->value->format('Y-m-d') === $date->format('Y-m-d');
    }

    public function isSameTime(\DateTimeInterface $date): bool
    {
        return $this->value->format('H:i:s') === $date->format('H:i:s');
    }

    public function getDateImmutable(): \DateTimeInterface
    {
        return $this->value;
    }

    public function format(string $format = DATE_ATOM): string
    {
        return $this->value->format($format);
    }
}
