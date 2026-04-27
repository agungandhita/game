<?php

namespace App\DTOs\Quiz;

readonly class LevelResultDTO
{
    public function __construct(
        public float $score,
        public int $stars,
        public int $totalCorrect,
        public int $totalQuestions,
        public int $totalTimeout,
    ) {
    }
}
