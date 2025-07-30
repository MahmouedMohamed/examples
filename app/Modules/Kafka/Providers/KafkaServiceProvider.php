<?php

namespace App\Modules\Kafka\Providers;

use App\Modules\Kafka\Interfaces\KafkaRepositoryInterface;
use App\Modules\Kafka\Interfaces\KafkaServiceInterface;
use App\Modules\Kafka\Repositories\KafkaRepository;
use App\Modules\Kafka\Services\KafkaService;
use Illuminate\Support\ServiceProvider;

class KafkaServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(KafkaServiceInterface::class, KafkaService::class);
        $this->app->bind(KafkaRepositoryInterface::class, KafkaRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadCommands();
    }

    /**
     * Load module commands.
     */
    private function loadCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \App\Modules\Kafka\Console\Commands\KafkaConsumeCommand::class,
            ]);
        }
    }
}
