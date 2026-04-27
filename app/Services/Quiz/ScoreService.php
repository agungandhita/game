<?php

namespace App\Services\Quiz;

class ScoreService implements ScoreServiceInterface
{
    public function calculate(int $totalBenar, int $totalSoal): float
    {
        if ($totalSoal === 0) {
            return 0.0;
        }

        return round(($totalBenar / $totalSoal) * 100, 2);
    }

    public function getStars(float $score): int
    {
        if ($score < 60) {
            return 1;
        }

        if ($score <= 89) {
            return 2;
        }

        return 3;
    }
}
