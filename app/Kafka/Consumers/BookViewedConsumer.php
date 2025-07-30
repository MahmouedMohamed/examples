<?php

namespace App\Kafka\Consumers;

use Junges\Kafka\Message\ConsumedMessage;

class BookViewedConsumer
{
    public function handle(ConsumedMessage $message): void
    {
        echo json_encode($message->getBody()).'\n';
    }
}
