<?php

namespace App\Modules\Book\Services;

use App\Modules\Book\Interfaces\BookRepositoryInterface;
use App\Modules\Book\Interfaces\BookServiceInterface;
use App\Modules\Book\Models\Book;
use Illuminate\Pagination\LengthAwarePaginator;

class BookService implements BookServiceInterface
{
    public function __construct(private BookRepositoryInterface $bookRepository) {}

    public function index($request): LengthAwarePaginator
    {
        return $this->bookRepository->index($request);
    }

    public function store($request): Book
    {
        return $this->bookRepository->store($request);
    }
}
