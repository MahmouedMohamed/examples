<?php

namespace App\Providers;

use App\Modules\Book\Providers\BookServiceProvider;
use App\Modules\Kafka\Providers\KafkaServiceProvider;
use App\Modules\Tenant\Providers\TenancyServiceProvider;
use App\Modules\User\Providers\UserServiceProvider;
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
        $this->app->register(UserServiceProvider::class);
        $this->app->register(TenancyServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
