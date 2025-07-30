<?php

namespace App\Modules\Kafka\Repositories;

use App\Modules\Kafka\Exceptions\KafkaException;
use App\Modules\Kafka\Interfaces\KafkaRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;

class KafkaRepository implements KafkaRepositoryInterface
{
    public function initialize()
    {
        try {
            $topics = config('kafka.topics');
            $broker = config('kafka.brokers', 'localhost:9092');

            foreach ($topics as $key => $topic) {
                Kafka::publish($broker)
                    ->onTopic($topic)
                    ->withMessage(new Message(
                        headers: ['header-key' => 'header-value'],
                        body: ['initialized' => true, 'timestamp' => now()->toISOString()]
                    ))
                    ->send();
            }

            return ['success' => true, 'message' => 'Kafka topics initialized successfully'];
        } catch (Exception $e) {
            Log::error('Kafka initialization failed', [
                'error' => $e->getMessage(),
                'topics' => config('kafka.topics')
            ]);

            throw KafkaException::connectionFailed(config('kafka.brokers', 'localhost:9092'));
        }
    }

    public function createMessage($topic, $headers, $payload)
    {
        try {
            $broker = config('kafka.brokers', 'localhost:9092');

            Kafka::publish($broker)
            ->onTopic($topic)
                ->withMessage(new Message(
                    headers: $headers,
                    body: $payload
                ))
                ->send();

            Log::info('Kafka message published', [
                'topic' => $topic,
                'headers' => $headers,
                'payload' => $payload
            ]);

            return ['success' => true, 'topic' => $topic];
        } catch (Exception $e) {
            Log::error('Kafka message publish failed', [
                'topic' => $topic,
                'error' => $e->getMessage(),
                'payload' => $payload
            ]);

            throw KafkaException::publishFailed($topic, $e->getMessage());
        }
    }
}
