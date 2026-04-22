<?php

namespace App\Services\Dashboard;

use Illuminate\Database\Eloquent\Collection;
use App\Models\World;
use App\Models\User;

interface DashboardServiceInterface
{
    public function getAllWorlds(?User $user = null): Collection;
    
    public function getWorldWithProgressBySlug(string $slug, int $userId): array;
}
