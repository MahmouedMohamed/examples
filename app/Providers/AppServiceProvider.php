<?php

namespace App\Providers;

use App\Interfaces\BookRepositoryInterface;
use App\Interfaces\BookServiceInterface;
use App\Interfaces\KafkaRepositoryInterface;
use App\Interfaces\KafkaServiceInterface;
use App\Repositories\BookRepository;
use App\Repositories\KafkaRepository;
use App\Services\BookService;
use App\Services\KafkaService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BookServiceInterface::class, BookService::class);
        $this->app->bind(BookRepositoryInterface::class, BookRepository::class);
        $this->app->bind(KafkaServiceInterface::class, KafkaService::class);
        $this->app->bind(KafkaRepositoryInterface::class, KafkaRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
