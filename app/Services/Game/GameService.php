<?php

namespace App\Services\Game;

use App\Models\Level;
use App\Models\UserProgress;
use App\Models\User;
use App\Models\Badge;
use Illuminate\Support\Facades\DB;

class GameService implements GameServiceInterface
{
    public function submitAnswers(int $userId, Level $level, array $answers): array
    {
        $levelQuestions = $level->questions()->with('options')->get();

        $correctCount = 0;
        foreach ($levelQuestions as $question) {
            $userAnswer = $answers[$question->id] ?? null;
            if ($userAnswer === null) continue;

            switch ($question->type) {
                case 'multiple_choice':
                    $option = $question->options->where('id', $userAnswer)->first();
                    if ($option && $option->is_correct) {
                        $correctCount++;
                    }
                    break;

                case 'essay':
                    if (!empty(trim($userAnswer))) {
                        $correctCount++;
                    }
                    break;

                case 'counting':
                    $correctOption = $question->options->where('is_correct', true)->first();
                    if ($correctOption && trim(strtolower($userAnswer)) === trim(strtolower($correctOption->content))) {
                        $correctCount++;
                    }
                    break;

                case 'matching':
                    if (is_array($userAnswer)) {
                        $allCorrect = true;
                        foreach ($userAnswer as $optionId => $matchValue) {
                            $option = $question->options->where('id', $optionId)->first();
                            if (!$option || trim(strtolower($matchValue)) !== trim(strtolower($option->content))) {
                                $allCorrect = false;
                                break;
                            }
                        }
                        if ($allCorrect && count($userAnswer) === $question->options->count()) {
                            $correctCount++;
                        }
                    }
                    break;
            }
        }

        $totalQuestions = $levelQuestions->count();
        $score = $totalQuestions > 0 ? (int) round(($correctCount / $totalQuestions) * $level->points_reward) : 0;
        $percentage = $totalQuestions > 0 ? ($correctCount / $totalQuestions) : 0;
        
        $stars = $this->calculateStars($percentage);
        $isCompleted = $correctCount === $totalQuestions && $totalQuestions > 0;

        $result = DB::transaction(function () use ($userId, $level, $score, $stars, $isCompleted) {
            $progress = UserProgress::firstOrNew([
                'user_id' => $userId,
                'level_id' => $level->id,
            ]);

            $isNewRecord = ! $progress->exists;
            $oldScore = $progress->score ?? 0;

            if ($isNewRecord || $score > $oldScore) {
                $progress->score = $score;
                $progress->is_completed = $isCompleted;
                $progress->stars = $stars;

                $diff = $score - $oldScore;
                if ($diff > 0) {
                    User::where('id', $userId)->increment('total_points', $diff);
                }
            }

            $progress->attempts = ($progress->attempts ?? 0) + 1;
            $progress->save();

            $this->checkAndAwardBadges($userId);

            return $progress;
        });

        return [
            'success' => true,
            'score' => $score,
            'stars' => $result->stars,
            'is_completed' => $result->is_completed,
            'correct_count' => $correctCount,
            'total_questions' => $totalQuestions,
            'message' => $isCompleted ? 'Hebat! Kamu pintar 🎉' : 'Yuk coba lagi pelan-pelan 😊',
        ];
    }

    private function calculateStars(float $percentage): int
    {
        if ($percentage >= 1) return 3;
        if ($percentage >= 0.7) return 2;
        if ($percentage >= 0.5) return 1;
        return 0;
    }

    private function checkAndAwardBadges(int $userId): void
    {
        $user = User::with('badges')->find($userId);
        if (! $user) return;

        $earnedBadgeIds = $user->badges->pluck('id')->toArray();
        $badges = Badge::all();

        foreach ($badges as $badge) {
            if (in_array($badge->id, $earnedBadgeIds)) continue;

            $earned = match ($badge->requirement_type) {
                'total_points' => $user->total_points >= $badge->requirement_value,
                'levels_completed' => UserProgress::where('user_id', $userId)->where('is_completed', true)->count() >= $badge->requirement_value,
                'attempts' => UserProgress::where('user_id', $userId)->sum('attempts') >= $badge->requirement_value,
                'login_streak' => $user->login_streak >= $badge->requirement_value,
                default => false,
            };

            if ($earned) {
                $user->badges()->attach($badge->id, ['earned_at' => now()]);
            }
        }
    }
}
