<?php

declare(strict_types=1);

namespace Project\Modules\Questions\Domain\ValueObjects;

use Exception;
use Symfony\Component\Uid\Uuid;

final readonly class QuestionId
{
    private function __construct(private Uuid $value)
    {
    }

    public static function generate(): self
    {
        return new self(Uuid::v7());
    }

    /**
     * @throws Exception
     */
    public static function fromString(string $value): self
    {
        if (Uuid::isValid($value)) {
            return new self(Uuid::fromString($value));
        }
        throw new Exception('Invalid question id');
    }

    public function equals(QuestionId $questionId): bool
    {
        return $this->getValue()->equals($questionId->getValue());
    }

    public function getValue(): Uuid
    {
        return $this->value;
    }
}
