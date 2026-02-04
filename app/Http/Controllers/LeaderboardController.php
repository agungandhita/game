<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $grade = $request->query('grade');

        $query = User::where('role', 'student')
            ->orderByDesc('total_points');

        if ($grade) {
            $query->where('grade', $grade);
        }

        $users = $query->take(50)->get();

        return view('leaderboard.index', [
            'users' => $users,
            'selectedGrade' => $grade,
        ]);
    }
}
