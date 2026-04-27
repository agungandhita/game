<?php

namespace App\Services\Quiz;

use App\DTOs\Quiz\AnswerResultDTO;
use App\DTOs\Quiz\LevelResultDTO;
use App\DTOs\Quiz\QuizSessionDTO;
use App\DTOs\Quiz\UserProgressDTO;
use App\Enums\SessionStatus;
use App\Models\Quiz\Level;
use App\Models\Quiz\LevelResult;
use App\Models\Quiz\Option;
use App\Models\Quiz\Question;
use App\Models\Quiz\QuizAnswer;
use App\Models\Quiz\QuizSession;

class QuizService implements QuizServiceInterface
{
    public function __construct(
        private ScoreServiceInterface $scoreService
    ) {
    }

    public function startSession(string $userId, string $levelId): QuizSessionDTO
    {
        $level = Level::with('questions')->findOrFail($levelId);
        
        $session = QuizSession::create([
            'user_id' => $userId,
            'level_id' => $levelId,
            'started_at' => now(),
            'status' => SessionStatus::InProgress,
        ]);

        return new QuizSessionDTO(
            sessionId: $session->id,
            userId: $session->user_id,
            levelId: $session->level_id,
            currentQuestionIndex: 1,
            totalQuestions: $level->questions->count(),
            timePerQuestion: $level->time_per_question,
        );
    }

    public function getCurrentQuestion(QuizSession $session): ?Question
    {
        $questions = $session->level->questions()->orderBy('order')->get();
        $answeredQuestionIds = $session->answers()->pluck('question_id')->toArray();
        
        return $questions->firstWhere(fn($q) => !in_array($q->id, $answeredQuestionIds));
    }

    public function submitAnswer(string $sessionId, string $questionId, ?string $optionId, bool $isTimeout = false, ?int $timeSpent = null): AnswerResultDTO
    {
        $isCorrect = false;
        $correctOption = null;

        $question = Question::with('options')->find($questionId);
        if ($question) {
            $correctOption = $question->options->firstWhere('is_correct', true);
        }

        if (!$isTimeout && $optionId) {
            $option = Option::find($optionId);
            $isCorrect = $option && $option->is_correct;
        }

        QuizAnswer::create([
            'session_id' => $sessionId,
            'question_id' => $questionId,
            'selected_option_id' => $isTimeout ? null : $optionId,
            'is_correct' => $isCorrect,
            'is_timeout' => $isTimeout,
            'time_spent' => $timeSpent,
        ]);

        return new AnswerResultDTO(
            isCorrect: $isCorrect,
            isTimeout: $isTimeout,
            correctOptionId: $correctOption?->id,
            correctOptionLabel: $correctOption?->label->value,
            timeSpent: $timeSpent
        );
    }

    public function finishSession(string $sessionId): LevelResultDTO
    {
        $session = QuizSession::with(['answers', 'level.questions'])->findOrFail($sessionId);

        $totalQuestions = $session->level->questions->count();
        $totalCorrect = $session->answers->where('is_correct', true)->count();
        $totalTimeout = $session->answers->where('is_timeout', true)->count();

        $score = $this->scoreService->calculate($totalCorrect, $totalQuestions);
        $stars = $this->scoreService->getStars($score);

        $session->update([
            'status' => SessionStatus::Completed,
            'finished_at' => now(),
        ]);

        $result = LevelResult::updateOrCreate(
            [
                'user_id' => $session->user_id,
                'level_id' => $session->level_id,
            ],
            [
                'score' => $score,
                'stars' => $stars,
                'total_correct' => $totalCorrect,
                'total_questions' => $totalQuestions,
                'total_timeout' => $totalTimeout,
                'completed_at' => now(),
            ]
        );

        return new LevelResultDTO(
            score: (float) $result->score,
            stars: $result->stars,
            totalCorrect: $result->total_correct,
            totalQuestions: $result->total_questions,
            totalTimeout: $result->total_timeout,
        );
    }

    public function getUserProgress(string $userId): array
    {
        $levels = Level::with('grade')->orderBy('grade_id')->orderBy('order')->get();
        $results = LevelResult::where('user_id', $userId)->get()->keyBy('level_id');

        $progressArray = [];
        $isNextUnlocked = true;

        foreach ($levels as $level) {
            $result = $results->get($level->id);
            
            // Level 1 tiap kelas selalu unlocked
            if ($level->order === 1) {
                $isNextUnlocked = true;
            }

            $status = 'locked';
            if ($result) {
                $status = 'completed';
            } elseif ($isNextUnlocked) {
                $status = 'unlocked';
            }

            $progressArray[] = new UserProgressDTO(
                levelId: $level->id,
                levelName: $level->name,
                gradeId: $level->grade_id,
                status: $status,
                score: $result ? (float) $result->score : null,
                stars: $result ? $result->stars : null,
            );

            // Update isNextUnlocked for the next iteration (within the same grade)
            if ($result && $result->score >= 60) {
                $isNextUnlocked = true;
            } else {
                $isNextUnlocked = false;
            }
        }

        return $progressArray;
    }
}
