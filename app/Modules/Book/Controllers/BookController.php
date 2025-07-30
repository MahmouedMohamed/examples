<?php

namespace App\Modules\Book\Controllers;

use App\Modules\Book\Interfaces\BookServiceInterface;
use App\Modules\Book\Requests\StoreBookRequest;
use App\Modules\Book\Resources\BookCollectionResource;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;

class BookController
{
    use ApiResponse;

    public function __construct(private BookServiceInterface $bookService)
    {
        // ...
    }

    public function index(Request $request)
    {
        try {
            $books = $this->bookService->index($request);

            return $this->success('Books retrieved successfully', new BookCollectionResource($books));
        } catch (Exception $e) {
            return $this->error('Failed to retrieve books: '.$e->getMessage());
        }
    }

    public function store(StoreBookRequest $request)
    {
        try {
            $book = $this->bookService->store($request);

            return $this->success('Book created successfully', $book);
        } catch (Exception $e) {
            return $this->error('Failed to create book: '.$e->getMessage());
        }
    }
}
