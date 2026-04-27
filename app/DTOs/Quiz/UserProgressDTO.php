<?php

namespace App\DTOs\Quiz;

readonly class UserProgressDTO
{
    public function __construct(
        public string $levelId,
        public string $levelName,
        public string $gradeId,
        public string $status, // locked, unlocked, completed
        public ?float $score,
        public ?int $stars,
    ) {
    }
}
