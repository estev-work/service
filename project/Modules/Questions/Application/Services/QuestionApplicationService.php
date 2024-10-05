<?php

namespace Project\Modules\Questions\Application\Services;

use Exception;
use Project\Base\Application\Bus\CommandBusInterface;
use Project\Modules\Questions\Api\DTO\CreateQuestionDTO;
use Project\Modules\Questions\Api\QuestionApiInterface;
use Project\Modules\Questions\Application\Commands\CreateQuestion\CreateQuestionCommand;
use Project\Modules\Questions\Application\Commands\CreateQuestion\CreateQuestionHandler;
use Project\Modules\Questions\Domain\Question;

readonly class QuestionApplicationService implements QuestionApiInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
    ) {
    }

    /**
     * @throws Exception
     */
    public function createQuestion(CreateQuestionDTO $data): string
    {
        $command = new CreateQuestionCommand(
            $data->title,
            $data->content
        );
        $this->commandBus->register(resolve(CreateQuestionHandler::class));
        /** @var Question $question */
        $question = $this->commandBus->handle($command);
        return $question->id()->getValue();
    }
}
