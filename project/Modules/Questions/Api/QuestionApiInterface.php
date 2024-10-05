<?php

namespace Project\Modules\Questions\Api;

use Project\Modules\Questions\Api\DTO\CreateQuestionDTO;

interface QuestionApiInterface
{
    public function createQuestion(CreateQuestionDTO $data): string;
}
