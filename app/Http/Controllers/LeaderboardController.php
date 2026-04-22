<?php

namespace App\Http\Controllers;

use App\Services\Leaderboard\LeaderboardServiceInterface;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function __construct(private LeaderboardServiceInterface $leaderboardService)
    {
    }

    public function index(Request $request)
    {
        $grade = $request->query('grade');

        $users = $this->leaderboardService->getTopStudents(50, $grade);

        return view('leaderboard.index', [
            'users' => $users,
            'selectedGrade' => $grade,
        ]);
    }
}
