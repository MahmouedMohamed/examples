<?php

namespace App\Modules\Kafka\Exceptions;

use Exception;

class KafkaException extends Exception
{
    /**
     * Create a new Kafka exception instance.
     */
    public function __construct(string $message = '', int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Create a new Kafka connection exception.
     */
    public static function connectionFailed(string $broker): self
    {
        return new self("Failed to connect to Kafka broker: {$broker}");
    }

    /**
     * Create a new Kafka publish exception.
     */
    public static function publishFailed(string $topic, string $reason = ''): self
    {
        $message = "Failed to publish message to topic '{$topic}'";
        if ($reason) {
            $message .= ": {$reason}";
        }

        return new self($message);
    }
}
