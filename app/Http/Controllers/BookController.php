<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Resources\BookCollectionResource;
use App\Interfaces\BookServiceInterface;
use App\Traits\ApiResponse;
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
        return $this->success(__('success'), new BookCollectionResource($this->bookService->index($request)));
    }

    public function store(StoreBookRequest $request)
    {
        return $this->success(__('success'), $this->bookService->store($request));
    }
}
