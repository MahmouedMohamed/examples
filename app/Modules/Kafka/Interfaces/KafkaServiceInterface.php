<?php

namespace App\Modules\Kafka\Interfaces;

interface KafkaServiceInterface
{
    public function initialize();

    public function createMessage($topic, $headers, $payload);
}
