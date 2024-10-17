<?php

declare(strict_types=1);

namespace Project\Endpoint\Console;

use Core\Console\ConsoleCommand;
use Project\Modules\Activities\Api\ActivityApiInterface;
use Project\Modules\Activities\Api\DTO\CreateActivityDTO;
use Symfony\Component\Console\Input\InputInterface;

final class CreateActivityConsoleCommand extends ConsoleCommand
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
            $service = resolve(ActivityApiInterface::class);
            $DTO = new CreateActivityDTO($title, $content);
            $activity = $service->createActivity($DTO);
            $diff = sprintf('%.6f sec.', microtime(true) - $start);
            $this->info('id: ' . $activity);
            $this->info('время создания: ' . $diff);
            return 1;
        } catch (\Exception $exception) {
            $this->log($exception->getMessage());
            return 0;
        }
    }
}