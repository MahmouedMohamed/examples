<?php

namespace App\Interfaces;

use App\Models\Book;
use Illuminate\Pagination\LengthAwarePaginator;

interface BookRepositoryInterface
{
    public function index($request): LengthAwarePaginator;

    public function store($request): Book;
}
