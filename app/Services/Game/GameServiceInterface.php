<?php

namespace App\Services\Game;

use App\Models\Level;
use App\Models\UserProgress;

interface GameServiceInterface
{
    public function submitAnswers(int $userId, Level $level, array $answers): array;
}
