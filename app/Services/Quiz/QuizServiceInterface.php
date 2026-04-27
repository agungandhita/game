<?php

namespace App\Services\Quiz;

use App\DTOs\Quiz\AnswerResultDTO;
use App\DTOs\Quiz\LevelResultDTO;
use App\DTOs\Quiz\QuizSessionDTO;
use App\Models\Quiz\Question;
use App\Models\Quiz\QuizSession;

interface QuizServiceInterface
{
    public function startSession(string $userId, string $levelId): QuizSessionDTO;
    public function getCurrentQuestion(QuizSession $session): ?Question;
    public function submitAnswer(string $sessionId, string $questionId, ?string $optionId, bool $isTimeout = false, ?int $timeSpent = null): AnswerResultDTO;
    public function finishSession(string $sessionId): LevelResultDTO;
    public function getUserProgress(string $userId): array;
}
