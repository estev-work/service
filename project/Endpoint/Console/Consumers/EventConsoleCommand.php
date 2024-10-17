<?php

declare(strict_types=1);

namespace Project\Endpoint\Console\Consumers;

use Core\Console\ConsoleCommand;
use Exception;
use Project\Base\Application\Bus\MessageBrokerInterface;
use Project\Base\Application\Events\MessageHandlerInterface;
use RdKafka\Message;
use Symfony\Component\Console\Input\InputInterface;

final class EventConsoleCommand extends ConsoleCommand
{

    /**
     * @throws Exception
     */
    public function execute(InputInterface $input): int
    {
        $this->info('Starting to consume...');
        $broker = resolve(MessageBrokerInterface::class);
        $broker->subscribe(['activity.created']);
        while (true) {
            $consumer = $broker->getConsumer();
            $message = $consumer->consume(30 * 1000);
            if ($message->err) {
                if ($message->err === RD_KAFKA_RESP_ERR__PARTITION_EOF) {
                    $this->info('No more messages, waiting for more...');
                    continue;
                } elseif ($message->err === RD_KAFKA_RESP_ERR__TIMED_OUT) {
                    $this->warn('Kafka timed out.');
                    continue;
                }

                $this->warn($message->errstr());
                break;
            }

            $result = $this->processMessage($message);
            if ($result) {
                $consumer->commit();
            }
        }

        return 0;
    }

    /**
     * @throws Exception
     */
    private function processMessage(Message $message): bool
    {
        $handler = resolve(MessageHandlerInterface::class);
        $eventData = $handler->prepareMessage($message->key, $message->payload, $message->headers);
//        $handler->addListeners([
//            ...resolve(NotificationApiInterface::class)->getListeners(),
//            ...resolve(ActivityApiInterface::class)->getListeners()
//        ]);
        return true;
    }
}