<?php

namespace App\Services\Dashboard;

use App\Models\User;
use App\Models\World;
use Illuminate\Database\Eloquent\Collection;

class DashboardService implements DashboardServiceInterface
{
    public function getAllWorlds(?User $user = null): Collection
    {
        $query = World::orderBy('class');
        
        if ($user && $user->role === 'student' && $user->grade) {
            $query->where('class', $user->grade);
        }
        
        return $query->get();
    }

    public function getWorldWithProgressBySlug(string $slug, int $userId): array
    {
        $world = World::where('slug', $slug)->with('levels')->firstOrFail();
        $user = User::find($userId);

        $progress = collect();
        if ($user) {
            $progress = $user->progress()
                ->whereIn('level_id', $world->levels->pluck('id'))
                ->get()
                ->keyBy('level_id');
        }

        return [
            'world' => $world,
            'user' => $user,
            'progress' => $progress,
        ];
    }
}
