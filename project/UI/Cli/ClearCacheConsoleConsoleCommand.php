<?php

declare(strict_types=1);

namespace Project\UI\Cli;

use Core\Console\ConsoleCommand;
use Symfony\Component\Console\Input\InputInterface;

final class ClearCacheConsoleConsoleCommand extends ConsoleCommand
{
    public function execute(InputInterface $input): int
    {
        return 0;
    }
}