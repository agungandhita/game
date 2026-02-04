<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\Level;
use App\Models\QuestionOption;
use App\Models\User;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    public function show(Level $level)
    {
        $level->load(['questions.options', 'world']);

        return view('game.show', compact('level'));
    }

    public function submit(Request $request, Level $level)
    {
        // Validate input
        $validated = $request->validate([
            'answers' => 'required|array',
        ]);

        $answers = $validated['answers'];
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
                    // For kids, if they write something, we give credit
                    if (!empty(trim($userAnswer))) {
                        $correctCount++;
                    }
                    break;

                case 'counting':
                    // Math question: check if answer matches the value of any correct option
                    $correctOption = $question->options->where('is_correct', true)->first();
                    if ($correctOption && trim(strtolower($userAnswer)) === trim(strtolower($correctOption->content))) {
                        $correctCount++;
                    }
                    break;

                case 'matching':
                    // Matching: userAnswer is expected to be an array of option_id => matched_value
                    // For simplicity, we assume options store the correct "match" in a specific way
                    // or we check if all submitted matches are correct.
                    // Implementation depends on how matching is structured.
                    // Assuming userAnswer is an array where each key is an option ID and value is the matched content
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
        $score = 0;
        if ($totalQuestions > 0) {
            $score = (int) round(($correctCount / $totalQuestions) * $level->points_reward);
        }

        $percentage = ($totalQuestions > 0) ? ($correctCount / $totalQuestions) : 0;
        $stars = $this->calculateStars($percentage);
        $isCompleted = $correctCount === $totalQuestions;

        // Wrap in transaction
        $result = DB::transaction(function () use ($level, $score, $stars, $isCompleted) {
            $userId = Auth::id();
            $progress = UserProgress::firstOrNew([
                'user_id' => $userId,
                'level_id' => $level->id,
            ]);

            $isNewRecord = ! $progress->exists;
            $oldScore = $progress->score ?? 0;

            // Only update if new score is higher or first attempt
            if ($isNewRecord || $score > $oldScore) {
                $progress->score = $score;
                $progress->is_completed = $isCompleted;
                $progress->stars = $stars;

                // Calculate point difference
                $diff = $score - $oldScore;
                if ($diff > 0) {
                    User::where('id', $userId)->increment('total_points', $diff);
                }
            }

            $progress->attempts = ($progress->attempts ?? 0) + 1;
            $progress->save();

            // Award badges after updating progress
            $this->checkAndAwardBadges($userId);

            return $progress;
        });

        return response()->json([
            'success' => true,
            'score' => $score,
            'stars' => $result->stars,
            'is_completed' => $result->is_completed,
            'correct_count' => $correctCount,
            'total_questions' => $totalQuestions,
            'message' => $isCompleted ? 'Hebat! Kamu pintar 🎉' : 'Yuk coba lagi pelan-pelan 😊',
        ]);
    }

    private function calculateStars(float $percentage): int
    {
        if ($percentage >= 1) {
            return 3;
        } elseif ($percentage >= 0.7) {
            return 2;
        } elseif ($percentage >= 0.5) {
            return 1;
        }

        return 0;
    }

    private function checkAndAwardBadges(int $userId): void
    {
        $user = User::with('badges')->find($userId);
        if (! $user) {
            return;
        }

        $earnedBadgeIds = $user->badges->pluck('id')->toArray();
        $badges = Badge::all();

        foreach ($badges as $badge) {
            if (in_array($badge->id, $earnedBadgeIds)) {
                continue; // Already earned
            }

            $earned = false;

            switch ($badge->requirement_type) {
                case 'total_points':
                    $earned = $user->total_points >= $badge->requirement_value;
                    break;

                case 'levels_completed':
                    $completedLevels = UserProgress::where('user_id', $userId)
                        ->where('is_completed', true)
                        ->count();
                    $earned = $completedLevels >= $badge->requirement_value;
                    break;

                case 'attempts':
                    $totalAttempts = UserProgress::where('user_id', $userId)->sum('attempts');
                    $earned = $totalAttempts >= $badge->requirement_value;
                    break;

                case 'login_streak':
                    $earned = $user->login_streak >= $badge->requirement_value;
                    break;
            }

            if ($earned) {
                $user->badges()->attach($badge->id, ['earned_at' => now()]);
            }
        }
    }
}
