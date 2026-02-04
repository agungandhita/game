<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Question;
use App\Models\User;
use App\Models\UserProgress;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_questions' => Question::count(),
            'total_levels' => Level::count(),
            'total_attempts' => UserProgress::count(),
        ];

        return view('admin.dashboard.index', compact('stats'));
    }
}
