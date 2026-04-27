<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz\Grade;
use App\Models\Quiz\LevelResult;
use App\Models\User;
use App\Enums\UserRole;

class GradeController extends Controller
{
    public function index()
    {
        $grades = Grade::with(['levels.questions', 'levels.levelResults'])
            ->orderBy('order')
            ->get()
            ->map(function ($grade) {
                $grade->total_questions = $grade->levels->sum(fn($l) => $l->questions->count());
                $grade->total_students = $grade->levels
                    ->flatMap(fn($l) => $l->levelResults->pluck('user_id'))
                    ->unique()
                    ->count();
                return $grade;
            });

        return view('admin.grades.index', compact('grades'));
    }

    public function show(Grade $grade)
    {
        $grade->load(['levels.questions', 'levels.levelResults.user']);

        $levels = $grade->levels()->orderBy('order')->get()->map(function ($level) {
            $level->total_questions = $level->questions()->count();
            $level->avg_score = round($level->levelResults()->avg('score') ?? 0, 2);
            $level->total_students = $level->levelResults()->distinct('user_id')->count('user_id');
            return $level;
        });

        return view('admin.grades.show', compact('grade', 'levels'));
    }
}
