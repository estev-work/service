<?php

namespace Project\Modules\Questions\Domain\Events;

use Project\Base\Domain\Events\EventInterface;
use Project\Modules\Questions\Domain\Question;

readonly class QuestionCreatedEvent implements EventInterface
{
    public string $questionId;
    public string $title;
    public string $content;
    public string $createdAt;

    public function __construct(
        Question $question,
    ) {
        $this->questionId = $question->id()->getValue();
        $this->title = $question->title()->getOriginalText();
        $this->content = $question->content()->getOriginalText();
        $this->createdAt = $question->createdAt()->format();
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
