<?php

namespace App\Modules\Kafka\Repositories;

use App\Modules\Kafka\Interfaces\KafkaRepositoryInterface;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;

class KafkaRepository implements KafkaRepositoryInterface
{
    public function initialize()
    {
        $topics = config('kafka.topics');

        foreach ($topics as $key => $topic) {
            Kafka::publish('localhost:9092') // broker address
                ->onTopic($topic)
                ->withMessage(new Message(
                    headers: ['header-key' => 'header-value'],
                    body: ['initialized' => true] // actual payload
                ))
                ->send();
        }
    }

    public function createMessage($topic, $headers, $payload)
    {
        Kafka::publish('localhost:9092') // broker address
            ->onTopic($topic)
            ->withMessage(new Message(
                headers: $headers,
                body: $payload // actual payload
            ))
            ->send();
    }
}
