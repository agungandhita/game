<?php

namespace App\DTOs\Quiz;

readonly class AnswerResultDTO
{
    public function __construct(
        public bool $isCorrect,
        public bool $isTimeout,
        public ?string $correctOptionId,
        public ?string $correctOptionLabel,
        public ?int $timeSpent,
    ) {
    }
}
