<?php

declare(strict_types=1);

namespace App\Core\Command;

use App\Core\Config\Config;
use Symfony\Component\Console\Input\ArgvInput;

abstract class Command
{
    public function __construct(protected readonly ArgvInput $input)
    {
        $this->runBase();
    }

    abstract public function execute(): void;

    private function runBase(): void
    {
        try {
            $this->execute();
            $this->success('Success');
        } catch (\Exception $exception) {
            $this->printError($exception->getMessage());
            if (Config::get('console.error.trace')) {
                $this->printCommandClass($this::class, $exception->getTrace());
            }
            $this->warn('Command failed');
            exit(1);
        }
    }

    private function printCommandClass(string $message, mixed $trace): void
    {
        $color1 = "\033[34m";
        $color2 = "\033[96m";
        $resetColor = "\033[0m";

        print $color1 . 'CLASS: ' . $color2 . $message . "::class" . $resetColor . PHP_EOL;
        print $color1 . 'TRACE: ' . $color2 . json_encode($trace, JSON_PRETTY_PRINT) . "::class" . $resetColor . PHP_EOL;
    }

    private function printError(string $message): void
    {
        $color1 = "\033[91m";
        $resetColor = "\033[0m";

        print 'Error: ' . $color1 . $message . $resetColor . PHP_EOL;
    }

    protected function success(string $message): void
    {
        $color1 = "\033[32m";
        $resetColor = "\033[0m";

        print $color1 . $message . $resetColor . PHP_EOL;
    }

    protected function warn(string $message): void
    {
        $color1 = "\033[91m";
        $resetColor = "\033[0m";

        print $color1 . $message . $resetColor . PHP_EOL;
    }

    protected function log(string $message): void
    {
        $color1 = "\033[91m";
        $resetColor = "\033[0m";

        print $color1 . $message . $resetColor . PHP_EOL;
    }
}