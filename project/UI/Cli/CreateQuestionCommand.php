<?php

declare(strict_types=1);

namespace Project\UI\Cli;

use Core\Command\Command;
use Project\Modules\Questions\Api\DTO\CreateQuestionDTO;
use Project\Modules\Questions\Api\QuestionApiInterface;
use Symfony\Component\Console\Input\InputInterface;

final class CreateQuestionCommand extends Command
{
    public function execute(InputInterface $input): int
    {
        $title = $input->getParameterOption('--title');
        $content = $input->getParameterOption('--content');
        if (empty($title) || empty($content)) {
            return 0;
        }
        try {
            $service = resolve(QuestionApiInterface::class);
            $DTO = new CreateQuestionDTO($title, $content);
            $question = $service->createQuestion(
                $DTO
            );
            $this->info('id: ' . $question);
            return 1;
        } catch (\Exception $exception) {
            $this->log($exception->getMessage());
            return 0;
        }
    }
}