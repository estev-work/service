<?php

declare(strict_types=1);

namespace Project\Modules\Questions\Domain\ValueObjects;


use Exception;

final readonly class QuestionTitle
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
        throw new Exception('Invalid question title.');
    }

    public function equals(QuestionTitle $questionTitle): bool
    {
        if (strlen($this->getOriginalText()) === strlen($questionTitle->getOriginalText())
            && $this->getOriginalText() === $questionTitle->getOriginalText()
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
