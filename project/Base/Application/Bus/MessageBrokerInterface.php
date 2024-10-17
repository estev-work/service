<?php

namespace Project\Base\Application\Bus;

use RdKafka\KafkaConsumer;

interface MessageBrokerInterface
{
    /**
     * Публикует событие в брокер сообщений.
     *
     * @param string $topicName
     * @param string $key
     * @param array  $payload
     *
     * @return void
     */
    public function publish(
        string $topicName,
        string $key = '',
        string $payload = ''
    ): void;

    public function subscribe(array $topics): void;

    public function getConsumer(): KafkaConsumer;
}
