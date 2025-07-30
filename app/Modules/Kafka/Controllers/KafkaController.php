<?php

namespace App\Modules\Kafka\Controllers;

use App\Modules\Kafka\Interfaces\KafkaServiceInterface;
use App\Traits\ApiResponse;

class KafkaController
{
    use ApiResponse;

    public function __construct(private KafkaServiceInterface $kafkaService) {}

    public function __invoke()
    {
        return $this->success(__('success'), $this->kafkaService->initialize());
    }
}
