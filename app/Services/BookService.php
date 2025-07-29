<?php

namespace App\Services;

use App\Interfaces\BookRepositoryInterface;
use App\Interfaces\BookServiceInterface;
use App\Models\Book;
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
