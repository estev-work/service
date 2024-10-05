<?php

namespace Project\Modules\Questions\Application\Commands\CreateQuestion;

use Project\Base\Application\Commands\AbstractCommandHandler;
use Project\Base\Application\Commands\CommandHandlerInterface;
use Project\Base\Application\Commands\CommandInterface;
use Project\Common\Attributes\CommandHandler;
use Project\Modules\Questions\Domain\Question;
use Project\Modules\Questions\Domain\Repositories\QuestionRepositoryInterface;

#[CommandHandler(CreateQuestionCommand::class)]
class CreateQuestionHandler extends AbstractCommandHandler implements CommandHandlerInterface
{
    private QuestionRepositoryInterface $questionRepository;

    public function __construct(QuestionRepositoryInterface $repository)
    {
        $this->questionRepository = $repository;
    }

    /** @param CreateQuestionCommand $command */
    public function execute(CommandInterface $command): Question
    {
        $this->assertCorrectCommand($command, CreateQuestionCommand::class);
        $question = Question::createNew($command->getTitle(), $command->getContent());
        $this->questionRepository->saveQuestion($question);
        return $question;
    }
}
