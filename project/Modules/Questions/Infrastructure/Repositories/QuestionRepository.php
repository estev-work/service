<?php

namespace Project\Modules\Questions\Infrastructure\Repositories;

use Exception;
use Project\Base\Infrastructure\Repositories\Mysql\MysqlRepository;
use Project\Common\Attributes\Table;
use Project\Modules\Questions\Domain\Question;
use Project\Modules\Questions\Domain\Repositories\QuestionRepositoryInterface;

#[Table('questions')]
class QuestionRepository extends MysqlRepository implements QuestionRepositoryInterface
{
    /**
     * @throws Exception
     */
    public function saveQuestion(Question $question): string|null
    {
        try {
            $data = [
                'id' => $question->id()->getValue(),
                'title' => $question->title()->getOriginalText(),
                'content' => $question->content()->getOriginalText(),
                'created_at' => $question->createdAt()->format('Y-m-d H:i:s'),
            ];
            $query
                = 'INSERT INTO questions (id, title, content, created_at) VALUES(:id, :title, :content, :created_at) ON DUPLICATE KEY UPDATE id=:id, title=:title';
            $this->raw($query, $data);
            return $question->id()->getValue();
        } catch (\Throwable $exception) {
            return null;
        }
    }

    public function findByQuestionId(string $id): ?Question
    {
        throw new Exception('Not implemented');
    }

    public function findAllQuestions(): array
    {
        throw new Exception('Not implemented');
    }
}
