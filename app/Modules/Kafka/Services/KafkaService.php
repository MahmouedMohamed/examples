<?php

namespace App\Modules\Kafka\Services;

use App\Modules\Kafka\Interfaces\KafkaRepositoryInterface;
use App\Modules\Kafka\Interfaces\KafkaServiceInterface;

class KafkaService implements KafkaServiceInterface
{
    public function __construct(private KafkaRepositoryInterface $kafkaRepository) {}

    public function initialize()
    {
        return $this->kafkaRepository->initialize();
    }

    public function createMessage($topic, $headers, $payload)
    {
        return $this->kafkaRepository->createMessage($topic, $headers, $payload);
    }
}
