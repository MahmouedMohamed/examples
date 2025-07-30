<?php

namespace App\Modules\Kafka\Consumers;

use Junges\Kafka\Message\ConsumedMessage;

class BookUpdatedConsumer
{
    public function handle(ConsumedMessage $message): void
    {
        echo json_encode($message->getBody()).'\n';
    }
}
