<?php

namespace App\Repositories;

use App\Interfaces\BookRepositoryInterface;
use App\Models\Book;
use Illuminate\Pagination\LengthAwarePaginator;

class BookRepository implements BookRepositoryInterface
{
    public function index($request): LengthAwarePaginator
    {
        return Book::paginate();
    }

    public function store($request): Book
    {
        return Book::create([
            'publication_date' => $request->publication_date,
            'author' => $request->author,
            'title' => $request->title,
        ]);
    }
}
