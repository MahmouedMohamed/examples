<?php

namespace App\Modules\Kafka\Interfaces;

interface KafkaRepositoryInterface
{
    public function initialize();

    public function createMessage($topic, $headers, $payload);
}
