<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Models\Question;
use App\Models\Level;
use App\Models\UserProgress;

class AdminService implements AdminServiceInterface
{
    public function getDashboardStats(): array
    {
        return [
            'total_users' => User::count(),
            'total_questions' => Question::count(),
            'total_levels' => Level::count(),
            'total_attempts' => UserProgress::count(),
        ];
    }
}
