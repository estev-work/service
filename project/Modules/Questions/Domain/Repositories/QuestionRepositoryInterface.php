<?php

namespace Project\Modules\Questions\Domain\Repositories;

use Project\Modules\Questions\Domain\Question;

interface QuestionRepositoryInterface
{
    /**
     * @param Question $question
     *
     * @return string|null
     */
    public function saveQuestion(Question $question): string|null;

    /**
     * @param string $id
     *
     * @return Question|null
     */
    public function findByQuestionId(string $id): ?Question;

    /**
     * @return array
     */
    public function findAllQuestions(): array;
}
