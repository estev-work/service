<?php

namespace Project\Modules\Questions\Application\Commands\CreateQuestion;

use Exception;
use Project\Base\Application\Commands\AbstractCommandHandler;
use Project\Base\Application\Commands\CommandHandlerInterface;
use Project\Base\Application\Commands\CommandInterface;
use Project\Base\Application\Events\UnitOfWorkInterface;
use Project\Common\Attributes\Command;
use Project\Modules\Questions\Domain\Question;
use Project\Modules\Questions\Domain\Repositories\QuestionRepositoryInterface;

#[Command(command: CreateQuestionCommand::class)]
class CreateQuestionHandler extends AbstractCommandHandler implements CommandHandlerInterface
{
    private QuestionRepositoryInterface $questionRepository;
    private UnitOfWorkInterface $unitOfWork;

    public function __construct(
        QuestionRepositoryInterface $repository,
        UnitOfWorkInterface $unitOfWork,
    ) {
        $this->questionRepository = $repository;
        $this->unitOfWork = $unitOfWork;
    }

    /** @param CreateQuestionCommand $command
     * @throws Exception
     */
    public function execute(CommandInterface $command): Question
    {
        $this->assertCorrectCommand($command, CreateQuestionCommand::class);
        $question = Question::createNew($command->getTitle(), $command->getContent());
        $this->unitOfWork->registerAggregateRoot($question);
        $this->questionRepository->saveQuestion($question);
        $this->unitOfWork->commit();
        return $question;
    }
}
