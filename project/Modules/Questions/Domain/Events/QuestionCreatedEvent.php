<?php

namespace Project\Modules\Questions\Domain\Events;

use Project\Base\Domain\Events\EventInterface;

readonly class QuestionCreatedEvent implements EventInterface
{

    public function __construct(
        public string $questionId,
        public string $title,
        public string $content,
        public string $createdAt,
    ) {
    }

    public function getName(): string
    {
        return "question.created";
    }

    public function getPayload(): string
    {
        return serialize($this);
    }

    public function getKey(): string
    {
        return $this->questionId;
    }
}
