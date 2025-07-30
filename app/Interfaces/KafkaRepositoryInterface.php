<?php

namespace App\Interfaces;

interface KafkaRepositoryInterface
{
    public function initialize();

    public function createMessage($topic, $headers, $payload);
}
