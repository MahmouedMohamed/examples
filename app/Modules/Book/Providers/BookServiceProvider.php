<?php

namespace App\Modules\Book\Providers;

use App\Modules\Book\Interfaces\BookRepositoryInterface;
use App\Modules\Book\Interfaces\BookServiceInterface;
use App\Modules\Book\Repositories\BookRepository;
use App\Modules\Book\Services\BookService;
use Illuminate\Support\ServiceProvider;

class BookServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BookServiceInterface::class, BookService::class);
        $this->app->bind(BookRepositoryInterface::class, BookRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
