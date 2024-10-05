<?php

namespace Project\UI\Http\Controllers;

use Core\Http\Request;
use Core\Response\JsonResponse;
use Project\Modules\Questions\Api\DTO\CreateQuestionDTO;
use Project\Modules\Questions\Api\QuestionApiInterface;

class QuestionController
{
    private QuestionApiInterface $questionApplicationService;

    public function __construct(QuestionApiInterface $questionApplicationService)
    {
        $this->questionApplicationService = $questionApplicationService;
    }

    public function create(Request $request): \Psr\Http\Message\ResponseInterface
    {
        $data = $request->getParsedBody();
        $DTO = new CreateQuestionDTO($data['title'], $data['content']);

        $this->questionApplicationService->createQuestion($DTO);
        return JsonResponse::send(
            [
                'success' => true,
            ],
            202
        );
    }
}
