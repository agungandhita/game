<?php

namespace App\Services\Leaderboard;

use Illuminate\Database\Eloquent\Collection;

interface LeaderboardServiceInterface
{
    public function getTopStudents(int $limit = 50, string $grade = null): Collection;
}
