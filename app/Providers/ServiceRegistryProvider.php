<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ServiceRegistryProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Services\User\UserServiceInterface::class, \App\Services\User\UserService::class);
        $this->app->bind(\App\Services\Grade\GradeServiceInterface::class, \App\Services\Grade\GradeService::class);
        $this->app->bind(\App\Services\Admin\AdminServiceInterface::class, \App\Services\Admin\AdminService::class);
        $this->app->bind(\App\Services\Dashboard\DashboardServiceInterface::class, \App\Services\Dashboard\DashboardService::class);
        $this->app->bind(\App\Services\Setting\SettingServiceInterface::class, \App\Services\Setting\SettingService::class);
        $this->app->bind(\App\Services\Leaderboard\LeaderboardServiceInterface::class, \App\Services\Leaderboard\LeaderboardService::class);
        $this->app->bind(\App\Services\Question\QuestionServiceInterface::class, \App\Services\Question\QuestionService::class);
        $this->app->bind(\App\Services\Game\GameServiceInterface::class, \App\Services\Game\GameService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
