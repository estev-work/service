<?php

declare(strict_types=1);

namespace Project\Endpoint\Console\Consumers;

use Core\Console\ConsoleCommand;
use Exception;
use PhpAmqpLib\Message\AMQPMessage;
use Project\Base\Application\Bus\MessageBrokerInterface;
use Project\Base\Application\Events\MessageHandlerInterface;
use Project\Modules\Notifications\Api\NotificationApiInterface;
use Symfony\Component\Console\Input\InputInterface;

final class EventConsoleCommand extends ConsoleCommand
{
    /**
     * @throws Exception
     */
    public function execute(InputInterface $input): int
    {
        $this->logger->setChannel('rabbitmq-work');
        $queue = $input->getParameterOption('--queue');
        $this->info('Starting to consume...');

        /** @var MessageBrokerInterface $broker */
        $broker = resolve(MessageBrokerInterface::class);
        /** @var MessageHandlerInterface $handler */
        $handler = resolve(MessageHandlerInterface::class);
        /** @var NotificationApiInterface $notificationApi */
        $notificationApi = resolve(NotificationApiInterface::class);
        $handler->addListeners([
            ...$notificationApi->getListeners()
        ]);
        $callback = function (AMQPMessage $msg) use ($handler, $queue) :void {
            $this->logger->info('Новое сообщение в очереди '. $queue);
            $eventData = json_decode($msg->getBody(), true);
            $headers = $msg->get('application_headers')?->getNativeData();
            $result = $handler->handleMessage($msg->getRoutingKey(), $eventData, $headers);
            if ($result){
                $this->log('Message handled:');
                $this->log($msg->getBody());
                $msg->getChannel()->basic_ack($msg->getDeliveryTag());
            }else{
                $this->log('Message unhandled:');
                $this->log($msg->getBody());
            }
        };
        $broker->subscribe($queue, $callback);
        return 0;
    }
}