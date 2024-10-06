<?php

declare(strict_types=1);

namespace Project\Endpoint\Console;

use Core\Console\ConsoleCommand;
use Project\Modules\Questions\Api\DTO\CreateQuestionDTO;
use Project\Modules\Questions\Api\QuestionApiInterface;
use Symfony\Component\Console\Input\InputInterface;

final class CreateQuestionConsoleConsoleCommand extends ConsoleCommand
{
    public function execute(InputInterface $input): int
    {
        $title = $input->getParameterOption('--title');
        $content = $input->getParameterOption('--content');
        if (empty($title) || empty($content)) {
            return 0;
        }
        try {
            $start = microtime(true);
            $service = resolve(QuestionApiInterface::class);
            $DTO = new CreateQuestionDTO($title, $content);
            $question = $service->createQuestion($DTO);
            $diff = sprintf('%.6f sec.', microtime(true) - $start);
            $this->info('id: ' . $question);
            $this->info('время создания: ' . $diff);
            return 1;
        } catch (\Exception $exception) {
            $this->log($exception->getMessage());
            return 0;
        }
    }
}