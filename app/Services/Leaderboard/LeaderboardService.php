<?php

namespace App\Services\Leaderboard;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class LeaderboardService implements LeaderboardServiceInterface
{
    public function getTopStudents(int $limit = 50, string $grade = null): Collection
    {
        $query = User::where('role', 'student')
            ->orderByDesc('total_points');

        if ($grade) {
            $query->where('grade', $grade);
        }

        return $query->take($limit)->get();
    }
}
