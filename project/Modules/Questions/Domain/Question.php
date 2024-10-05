<?php

namespace Project\Modules\Questions\Domain;

use Project\Base\Domain\BaseAggregate;
use Project\Modules\Questions\Domain\Events\QuestionCreatedEvent;
use Project\Modules\Questions\Domain\ValueObjects\DateValue;
use Project\Modules\Questions\Domain\ValueObjects\QuestionContent;
use Project\Modules\Questions\Domain\ValueObjects\QuestionId;
use Project\Modules\Questions\Domain\ValueObjects\QuestionTitle;

class Question extends BaseAggregate
{
    private readonly QuestionId $id;
    private QuestionTitle $title;
    private QuestionContent $content;
    private readonly DateValue $createdAt;
    private ?DateValue $updatedAt;

    private function __construct(
        QuestionId $id,
        QuestionTitle $title,
        QuestionContent $content,
        DateValue $createdAt,
        ?DateValue $updatedAt
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function from(
        string $id,
        string $title,
        string $content,
        string $createdAt,
        string $updatedAt = null
    ): self {
        $questionId = QuestionId::fromString($id);
        $questionTitle = QuestionTitle::fromString($title);
        $questionContent = QuestionContent::fromString($content);
        return new self(
            $questionId,
            $questionTitle,
            $questionContent,
            DateValue::fromString($createdAt),
            $updatedAt ? DateValue::fromString($updatedAt) : null
        );
    }

    public static function createNew(string $title, string $content): self
    {
        $questionId = QuestionId::generate();
        $questionTitle = QuestionTitle::fromString($title);
        $questionContent = QuestionContent::fromString($content);
        $instance = new Question($questionId, $questionTitle, $questionContent, DateValue::make(), null);
        $instance->recordEvent(
            new QuestionCreatedEvent(
                $instance->id()->getValue(),
                $instance->title()->getOriginalText(),
                $instance->content()->getOriginalText(),
                $instance->createdAt()->format()
            )
        );
        return $instance;
    }

    public function id(): QuestionId
    {
        return $this->id;
    }

    public function title(): QuestionTitle
    {
        return $this->title;
    }

    public function changeTitle(string $title): void
    {
        $this->title = QuestionTitle::fromString($title);
        $this->updatedAt = DateValue::make();
    }

    public function content(): QuestionContent
    {
        return $this->content;
    }

    public function changeContent(string $content): void
    {
        $this->content = QuestionContent::fromString($content);
        $this->updatedAt = DateValue::make();
    }

    public function createdAt(): DateValue
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?DateValue
    {
        return $this->updatedAt;
    }
}
