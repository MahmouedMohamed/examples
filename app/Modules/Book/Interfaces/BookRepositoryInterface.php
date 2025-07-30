<?php

namespace App\Modules\Book\Interfaces;

use App\Modules\Book\Models\Book;
use Illuminate\Pagination\LengthAwarePaginator;

interface BookRepositoryInterface
{
    public function index($request): LengthAwarePaginator;

    public function store($request): Book;
}
