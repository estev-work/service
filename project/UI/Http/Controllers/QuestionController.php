<?php

namespace Project\UI\Http\Controllers;

use Core\Http\Request;
use Core\Response\JsonResponse;
use Project\Modules\Questions\Api\DTO\CreateQuestionDTO;
use Project\Modules\Questions\Api\QuestionApiInterface;
use Psr\Log\LoggerInterface;

class QuestionController
{
    private QuestionApiInterface $questionApplicationService;
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger, QuestionApiInterface $questionApplicationService)
    {
        $this->logger = $logger;
        $this->questionApplicationService = $questionApplicationService;
    }

    public function create(Request $request): \Psr\Http\Message\ResponseInterface
    {
        $data = $request->getQueryParams();
        $DTO = new CreateQuestionDTO($data['title'], $data['content']);

        $this->questionApplicationService->createQuestion($DTO);
        return JsonResponse::send(
            202,
            [
                'success' => true,
            ]
        );
    }
}
