<?php

declare(strict_types=1);

namespace Project\UI\Cli;

use Core\Command\Command;
use Symfony\Component\Console\Input\InputInterface;

final class ClearCacheCommand extends Command
{
    public function execute(InputInterface $input): int
    {
        return 0;
    }
}