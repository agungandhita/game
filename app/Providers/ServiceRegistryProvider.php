<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Quiz\QuizServiceInterface;
use App\Services\Quiz\QuizService;
use App\Services\Quiz\ScoreServiceInterface;
use App\Services\Quiz\ScoreService;

class ServiceRegistryProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(QuizServiceInterface::class, QuizService::class);
        $this->app->bind(ScoreServiceInterface::class, ScoreService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
