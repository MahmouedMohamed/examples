<?php

namespace App\Services;

use App\Interfaces\KafkaRepositoryInterface;
use App\Interfaces\KafkaServiceInterface;

class KafkaService implements KafkaServiceInterface
{
    public function __construct(private KafkaRepositoryInterface $kafkaRepository) {}

    public function initialize()
    {
        return $this->kafkaRepository->initialize();
    }
}
