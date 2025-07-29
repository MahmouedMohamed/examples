<?php

namespace App\Interfaces;

use App\Models\Book;
use Illuminate\Pagination\LengthAwarePaginator;

interface BookServiceInterface
{
    public function index($request): LengthAwarePaginator;

    public function store($request): Book;
}
