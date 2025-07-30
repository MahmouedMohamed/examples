<?php

namespace App\Providers;

use App\Modules\Book\Providers\BookServiceProvider;
use App\Modules\Kafka\Providers\KafkaServiceProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register module service providers
        $this->app->register(BookServiceProvider::class);
        $this->app->register(KafkaServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
