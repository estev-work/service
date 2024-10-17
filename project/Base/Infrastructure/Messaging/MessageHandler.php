<?php

declare(strict_types=1);

namespace Project\Base\Infrastructure\Messaging;

use Core\Logger\AppLoggerInterface;
use Project\Base\Application\Events\EventDataInterface;
use Project\Base\Application\Events\EventHandlerInterface;
use Project\Base\Application\Events\EventListenerInterface;
use Project\Base\Application\Events\MessageHandlerInterface;

final class MessageHandler implements MessageHandlerInterface
{
    private AppLoggerInterface $logger;

    public function __construct(AppLoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    private EventHandlerInterface $eventHandler;

    public function prepareMessage(string $key, string $payload, array $headers): EventDataInterface
    {
        $this->logger->debug("Prepared $key payload: $payload");
        $payloadData = json_decode($payload, true);
        return new EventData($key, $payloadData);
    }

    /**
     * @param EventListenerInterface[] $listeners
     *
     * @return void
     */
    public function addListeners(array $listeners): void
    {
        //TODO
    }
}