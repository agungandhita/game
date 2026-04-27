<?php

namespace App\Services\Quiz;

interface ScoreServiceInterface
{
    public function calculate(int $totalBenar, int $totalSoal): float;
    public function getStars(float $score): int;
}
