<?php

namespace Project\Providers\Common;

use Illuminate\Support\ServiceProvider;
use Project\Base\Infrastructure\Messaging\EventHandlerRegistrar;
use Project\Base\Infrastructure\Messaging\Kafka\KafkaMessageBroker;
use Project\Base\Infrastructure\Messaging\MessageBrokerInterface;
use RdKafka\Conf;
use RdKafka\KafkaConsumer;
use RdKafka\Message;
use RdKafka\Producer;

class EventsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Регистрируем MessageBrokerInterface
        $this->app->bind(MessageBrokerInterface::class, function ($app) {
            return $this->createKafkaMessageBroker();
        });
    }


    /**
     * Создание Kafka продюсера и консюмера
     */
    private function createKafkaMessageBroker(): KafkaMessageBroker
    {
        $producerConf = new Conf();
        $producerConf->set('metadata.broker.list', config('services.kafka.broker'));
        $producerConf->setDrMsgCb(function (Producer $producer, Message $message) {
            if ($message->err) {
//                Log::channel('kafka')->error(
//                    "Message delivery failed: " . rd_kafka_err2str($message->err)
//                );
            } else {
//                Log::channel('kafka')->info(
//                    "Message delivered to partition " . $message->partition . " at offset " . $message->offset
//                );
            }
        });
        $producer = new Producer($producerConf);

        $consumerConf = new Conf();
        $consumerConf->set('metadata.broker.list', config('services.kafka.broker'));
        $consumerConf->set('client.id', config('services.kafka.client_id', 'core-consumers-client'));
        $consumerConf->set('group.id', config('services.kafka.group_id', 'core-consumers-group'));
        $consumerConf->set('auto.offset.reset', 'earliest');
        $consumerConf->set('enable.auto.commit', 0);
        $consumer = new KafkaConsumer($consumerConf);

        return new KafkaMessageBroker($producer, $consumer);
    }
}
