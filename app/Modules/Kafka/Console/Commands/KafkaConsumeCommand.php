<?php

namespace App\Modules\Kafka\Console\Commands;

use App\Modules\Kafka\Consumers\BookCreatedConsumer;
use App\Modules\Kafka\Consumers\BookUpdatedConsumer;
use App\Modules\Kafka\Consumers\BookViewedConsumer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\ConsumedMessage;

class KafkaConsumeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kafka:consume-inline';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume Kafka messages using inline handler';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Kafka::consumer([
            'book-created',
            'book-updated',
            'book-viewed',
        ])
            ->withHandler(function (ConsumedMessage $message) {
                $topic = $message->getTopicName();

                // Dispatch to correct handler based on topic
                match ($topic) {
                    'book-created' => app(BookCreatedConsumer::class)->handle($message),
                    'book-updated' => app(BookUpdatedConsumer::class)->handle($message),
                    'book-viewed' => app(BookViewedConsumer::class)->handle($message),
                    default => Log::warning("No handler for topic {$topic}")
                };
            })
            ->build()
            ->consume();
    }
}
