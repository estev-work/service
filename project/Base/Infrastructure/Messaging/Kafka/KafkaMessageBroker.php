<?php

namespace Project\Base\Infrastructure\Messaging\Kafka;

use Project\Base\Application\Bus\MessageBrokerInterface;
use Project\Base\Domain\Events\EventInterface;
use Project\Common\EventHelper;
use RdKafka\Exception;
use RdKafka\KafkaConsumer;
use RdKafka\KafkaConsumer as RdKafkaConsumer;
use RdKafka\Producer as RdKafkaProducer;

class KafkaMessageBroker implements MessageBrokerInterface
{
    private RdKafkaProducer $producer;
    private RdKafkaConsumer $consumer;

    public function __construct(RdKafkaProducer $producer, RdKafkaConsumer $consumer)
    {
        $this->producer = $producer;
        $this->consumer = $consumer;
    }

    /**
     * @param string $topicName
     * @param string $key
     * @param string $payload
     *
     * @return void
     */
    public function publish(
        string $topicName,
        string $key = '',
        string $payload = '',
    ): void {
        $topic = $this->producer->newTopic($topicName);
        $topic->produce(
            partition: RD_KAFKA_PARTITION_UA,
            msgflags: 0,
            payload: $payload,
            key: $key,
        );
        $this->producer->poll(10);
    }

    /**
     * @param EventInterface[] $topics
     *
     * @return void
     * @throws Exception
     */
    public function subscribe(array $topics): void
    {
        $subscribeTopics = [];
        /** @var string $topic */
        foreach ($topics as $topic) {
            $subscribeTopics[] = EventHelper::getTopicName($topic);
        }
        $this->consumer->subscribe($subscribeTopics);
    }

    /**
     * @return RdKafkaConsumer
     */
    public function getConsumer(): KafkaConsumer
    {
        return $this->consumer;
    }
}
