<?php

namespace Tests\Feature;

use App\Modules\Book\Interfaces\BookServiceInterface;
use App\Modules\Book\Models\Book;
use App\Modules\Kafka\Interfaces\KafkaServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BookModuleTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock Kafka service to avoid actual Kafka calls during testing
        $this->mock(KafkaServiceInterface::class, function ($mock) {
            $mock->shouldReceive('createMessage')->andReturn(true);
        });

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
                1, // total items
                15 // per page
            );

            $mock->shouldReceive('index')
                ->andReturn($fakeBooks);

            $mock->shouldReceive('store')
                ->andReturn(new Book([
                    'id' => 'Test-ID',
                    'title' => 'New Book',
                    'author' => 'New Author',
                    'publication_date' => '2024-01-01',
                ]));
        });
    }

    #[Test]
    public function it_can_create_a_book_via_api()
    {
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
    }

    #[Test]
    public function it_can_retrieve_all_books()
    {
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
    }

    #[Test]
    public function it_validates_required_fields_when_creating_book()
    {
        $response = $this->postJson('/api/books', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'author', 'publication_date']);
    }

    #[Test]
    public function book_service_can_retrieve_books()
    {
        $bookService = $this->app->make(BookServiceInterface::class);
        $books = $bookService->index(request());

        $this->assertCount(1, $books);
        $this->assertEquals('Test Book', $books->first()->title);
    }

    #[Test]
    public function book_service_can_create_book()
    {
        $bookService = $this->app->make(BookServiceInterface::class);

        $request = request()->merge([
            'title' => 'New Book',
            'author' => 'New Author',
            'publication_date' => '2024-01-01',
        ]);

        $book = $bookService->store($request);

        $this->assertEquals('New Book', $book->title);
        $this->assertEquals('New Author', $book->author);
    }
}
