<?php

namespace App\DTOs\Quiz;

use App\Models\Quiz\QuizSession;

readonly class QuizSessionDTO
{
    public function __construct(
        public string $sessionId,
        public string $userId,
        public string $levelId,
        public int $currentQuestionIndex,
        public int $totalQuestions,
        public int $timePerQuestion,
    ) {
    }
}
