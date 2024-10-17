<?php

declare(strict_types=1);

namespace Project\Endpoint\Console;

use Core\Console\ConsoleCommand;
use Symfony\Component\Console\Input\InputInterface;

final class ClearCacheConsoleCommand extends ConsoleCommand
{
    public function execute(InputInterface $input): int
    {
        return 0;
    }
}