<?php

namespace App\Interfaces;

interface KafkaServiceInterface
{
    public function initialize();

    public function createMessage($topic, $headers, $payload);
}
