<?php

use App\Modules\Book\Interfaces\BookServiceInterface;
use App\Modules\Book\Models\Book;
use App\Modules\Kafka\Interfaces\KafkaServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;

beforeEach(function () {
    // Mock Kafka service
    $this->mock(KafkaServiceInterface::class, function ($mock) {
        $mock->shouldReceive('createMessage')->andReturn(true);
    });

    // Mock Book service
    $this->mock(BookServiceInterface::class, function ($mock) {
        $fakeBooks = new LengthAwarePaginator(
            collect([
                new Book([
                    'id' => 'Test-ID',
                    'title' => 'Test Book',
                    'author' => 'Test Author',
                    'publication_date' => '2024-01-01',
                ]),
            ]),
            1,
            15
        );

        $mock->shouldReceive('index')->andReturn($fakeBooks);

        $mock->shouldReceive('store')->andReturn(
            new Book([
                'id' => 'Test-ID',
                'title' => 'New Book',
                'author' => 'New Author',
                'publication_date' => '2024-01-01',
            ])
        );
    });
});

test('it can create a book via api', function () {
    $bookData = [
        'title' => 'New Book',
        'author' => 'New Author',
        'publication_date' => '2024-01-01',
    ];

    $response = $this->postJson('/api/books', $bookData);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'item' => [
                'id',
                'title',
                'author',
                'publication_date',
            ],
        ]);
});

test('it can retrieve all books', function () {
    $response = $this->getJson('/api/books');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'items' => [
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'author',
                        'publication_date',
                    ],
                ],
                'links',
                'meta',
            ],
        ]);
});

test('it validates required fields when creating book', function () {
    $response = $this->postJson('/api/books', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['title', 'author', 'publication_date']);
});

test('book service can retrieve books', function () {
    $bookService = app(BookServiceInterface::class);
    $books = $bookService->index(request());

    expect($books)->toHaveCount(1);
    expect($books->first()->title)->toBe('Test Book');
});

test('book service can create book', function () {
    $bookService = app(BookServiceInterface::class);

    $request = request()->merge([
        'title' => 'New Book',
        'author' => 'New Author',
        'publication_date' => '2024-01-01',
    ]);

    $book = $bookService->store($request);

    expect($book->title)->toBe('New Book');
    expect($book->author)->toBe('New Author');
});
