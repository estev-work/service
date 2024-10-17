<?php

declare(strict_types=1);

namespace Core\Logger;

use Psr\Log\LogLevel;

final class FileLogger implements AppLoggerInterface
{
    private array $config;
    private string $channel;
    private bool $isProduction;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->channel = $config['logger']['channel'] ?? 'default';
        $this->isProduction = $config['logger']['production'] ?? false;
    }

    public function setChannel(string $channel): void
    {
        if (isset($this->config['channels'][$channel])) {
            $this->channel = $channel;
        } else {
            throw new \InvalidArgumentException("Канал {$channel} не найден.");
        }
    }

    private function getLogFileName(): string
    {
        $channelConfig = $this->config['channels'][$this->channel];
        $fileName = $channelConfig['file']['name'] ?? 'application';

        if ($channelConfig['daily'] ?? false) {
            $fileName .= '_' . date('Y-m-d');
        }

        return $fileName . '.log';
    }

    private function getLogDirectory(): string
    {
        $channelConfig = $this->config['channels'][$this->channel];
        return __DIR__ . '/../../logs/' . $channelConfig['path'] ?? 'logs';
    }

    private function writeLog(string $level, string $message, array $context = []): void
    {
        $date = date('Y-m-d H:i:s');
        $logMessage = sprintf(
            "[%s] %s: %s %s\n", $date, strtoupper($level), $message, json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );

        $logDirectory = $this->getLogDirectory();
        $fileName = $this->getLogFileName();

        if (!file_exists($logDirectory)) {
            mkdir($logDirectory, 0777, true);
        }

        file_put_contents($logDirectory . '/' . $fileName, $logMessage, FILE_APPEND);

        $this->cleanupOldLogs();
    }

    private function cleanupOldLogs(): void
    {
        $channelConfig = $this->config['channels'][$this->channel];
        $daysToKeep = $channelConfig['days'] ?? 7;
        $logDirectory = $this->getLogDirectory();

        foreach (scandir($logDirectory) as $file) {
            $filePath = $logDirectory . '/' . $file;
            if (is_file($filePath) && (filemtime($filePath) < (time() - $daysToKeep * 86400))) {
                unlink($filePath);
            }
        }
    }

    public function log($level, $message, array $context = []): void
    {
        // В production логируется только critical
        if ($this->isProduction && $level !== LogLevel::CRITICAL) {
            return;
        }

        $this->writeLog($level, $message, $context);
    }

    public function emergency($message, array $context = []): void
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    public function alert($message, array $context = []): void
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    public function critical($message, array $context = []): void
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    public function error($message, array $context = []): void
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    public function warning($message, array $context = []): void
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    public function notice($message, array $context = []): void
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    public function info($message, array $context = []): void
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    public function debug($message, array $context = []): void
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }
}