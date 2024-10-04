<?php

namespace Project\Modules\Questions\Application\Commands\CreateQuestion;


use Project\Base\Application\Commands\CommandInterface;

readonly class CreateQuestionCommand implements CommandInterface
{
    private string $title;
    private string $content;

    public function __construct(?string $title, ?string $content)
    {
        $this->title = $title ?? '';
        $this->content = $content ?? '';
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
