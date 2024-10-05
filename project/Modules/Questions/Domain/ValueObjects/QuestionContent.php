<?php

declare(strict_types=1);


namespace Project\Modules\Questions\Domain\ValueObjects;

use Nette\Schema\ValidationException;

final class QuestionContent
{
    private ?string $hash = null;

    private function __construct(private readonly string $value)
    {
    }

    public static function fromString(string $value): self
    {
        if ($value !== '') {
            return new self($value);
        }
        throw new ValidationException('Invalid question title.');
    }

    private function getHash(): string
    {
        if ($this->hash === null) {
            $this->hash = md5($this->getOriginalText());
        }
        return $this->hash;
    }

    public function equals(QuestionContent $questionTitle): bool
    {
        if ($this->getHash() === $questionTitle->getHash()) {
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
