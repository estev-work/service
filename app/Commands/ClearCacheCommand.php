<?php

declare(strict_types=1);

namespace App\Commands;

use App\Core\Command;

final class ClearCacheCommand extends Command
{
    public function execute(): void
    {
        $this->warn('Not implemented');
    }
}