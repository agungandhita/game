<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz\Level;
use App\Models\Quiz\LevelResult;
use App\Models\Quiz\Question;
use App\Models\Quiz\QuizSession;
use App\Models\User;
use App\Enums\UserRole;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_students'  => User::where('role', UserRole::Student)->count(),
            'total_questions' => Question::count(),
            'total_levels'    => Level::count(),
            'total_attempts'  => LevelResult::count(),
            'today_sessions'  => QuizSession::whereDate('started_at', Carbon::today())->count(),
            'avg_score'       => round(LevelResult::avg('score') ?? 0, 1),
        ];

        $topStudents = User::where('role', UserRole::Student)
            ->withAvg('levelResults as avg_score', 'score')
            ->orderByDesc('avg_score')
            ->take(5)
            ->get();

        // Chart: 7 hari terakhir
        $chartLabels = [];
        $chartData   = [];
        for ($i = 6; $i >= 0; $i--) {
            $date          = Carbon::today()->subDays($i);
            $chartLabels[] = $date->format('d M');
            $chartData[]   = QuizSession::whereDate('started_at', $date)->count();
        }

        return view('admin.dashboard.index', compact(
            'stats', 'topStudents', 'chartLabels', 'chartData'
        ));
    }
}
