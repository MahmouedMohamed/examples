<?php

namespace App\Modules\Kafka\Consumers;

use Junges\Kafka\Message\ConsumedMessage;

class BookCreatedConsumer
{
    public function handle(ConsumedMessage $message): void
    {
        echo json_encode($message->getBody()).'\n';
    }
}
