<?php

declare(strict_types=1);

namespace Core\Console;

use Core\Config\Config;
use Core\Logger\AppLoggerInterface;
use Exception;
use Symfony\Component\Console\Input\InputInterface;

abstract class ConsoleCommand
{
    protected readonly AppLoggerInterface $logger;

    /**
     * @throws Exception
     */
    public function __construct(protected readonly InputInterface $input)
    {
        $this->logger = resolve(AppLoggerInterface::class);
        $this->logger->setChannel(Config::get('console.log.chanel'));
        $this->runBase();
    }

    abstract public function execute(InputInterface $input): int;

    private function runBase(): void
    {
        try {
            $res = $this->execute($this->input);
            if (!$res) {
                throw new Exception('ConsoleCommand not executed');
            }
            $this->success('Success');
        } catch (\Throwable $exception) {
            $config = Config::get('console');
            $this->printError($exception->getMessage());
            if ($config['error']['trace']) {
                $this->printCommandClass($this::class, $exception->getTrace());
            }
            if ($config['log']['enable']) {
                $this->logger->warning($this::class, $exception->getTrace());
            }
            $this->warn('ConsoleCommand failed');
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

    /**
     * Зелёный текст
     *
     * @param string $message
     *
     * @return void
     */
    protected function success(string $message): void
    {
        $color1 = "\033[32m";
        $resetColor = "\033[0m";

        print $color1 . $message . $resetColor . PHP_EOL;
    }

    /**
     * Синий текст
     *
     * @param string $message
     *
     * @return void
     */
    protected function info(string $message): void
    {
        $color1 = "\033[34m";
        $resetColor = "\033[0m";

        print $color1 . $message . $resetColor . PHP_EOL;
    }

    /**
     * Красный текст
     *
     * @param string $message
     *
     * @return void
     */
    protected function warn(string $message): void
    {
        $color1 = "\033[91m";
        $resetColor = "\033[0m";

        print $color1 . $message . $resetColor . PHP_EOL;
    }

    /**
     * Бирюзовый текст
     *
     * @param string $message
     *
     * @return void
     */
    protected function log(string $message): void
    {
        $color1 = "\033[96m";
        $resetColor = "\033[0m";
        print $color1 . $message . $resetColor . PHP_EOL;
    }
}