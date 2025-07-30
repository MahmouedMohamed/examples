<?php

namespace App\Modules\Book\Controllers;

use App\Modules\Book\Interfaces\BookServiceInterface;
use App\Modules\Book\Requests\StoreBookRequest;
use App\Modules\Book\Resources\BookCollectionResource;
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
