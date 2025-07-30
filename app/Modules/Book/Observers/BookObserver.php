<?php

namespace App\Modules\Book\Observers;

use App\Modules\Book\Models\Book;
use App\Modules\Kafka\Interfaces\KafkaServiceInterface;

class BookObserver
{
    public function __construct(private KafkaServiceInterface $kafkaService) {}

    /**
     * Handle the Book "viewed" event.
     */
    public function viewed(Book $book): void
    {
        $this->kafkaService->createMessage(Book::VIEW_TOPIC, [], $book);
    }

    /**
     * Handle the Book "created" event.
     */
    public function created(Book $book): void
    {
        $this->kafkaService->createMessage(Book::CREATE_TOPIC, [], $book);
    }

    /**
     * Handle the Book "updated" event.
     */
    public function updated(Book $book): void
    {
        $this->kafkaService->createMessage(Book::UPDATE_TOPIC, [], $book);
    }

    /**
     * Handle the Book "deleted" event.
     */
    public function deleted(Book $book): void
    {
        //
    }

    /**
     * Handle the Book "restored" event.
     */
    public function restored(Book $book): void
    {
        //
    }

    /**
     * Handle the Book "force deleted" event.
     */
    public function forceDeleted(Book $book): void
    {
        //
    }
}
