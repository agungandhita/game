<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nickname' => 'required|string|exists:users,nickname',
        ]);

        $user = User::where('nickname', $request->nickname)->first();

        // Update login streak
        $this->updateLoginStreak($user);

        Auth::login($user);

        if ($user && $user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('dashboard');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nickname' => 'required|string|unique:users,nickname|max:20|alpha_dash',
            'grade' => 'required|integer|in:3,4,5',
        ]);

        $user = User::create([
            'nickname' => $request->nickname,
            'grade' => $request->grade,
            'role' => 'student',
            'total_points' => 0,
            'last_login_at' => now()->toDateString(),
            'login_streak' => 1,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('login');
    }

    private function updateLoginStreak(User $user): void
    {
        $today = Carbon::today();
        $lastLogin = $user->last_login_at ? Carbon::parse($user->last_login_at) : null;

        if ($lastLogin === null) {
            // First login ever
            $user->login_streak = 1;
        } elseif ($lastLogin->isSameDay($today)) {
            // Already logged in today, no change
            return;
        } elseif ($lastLogin->addDay()->isSameDay($today)) {
            // Consecutive day login
            $user->login_streak++;
        } else {
            // Streak broken
            $user->login_streak = 1;
        }

        $user->last_login_at = $today->toDateString();
        $user->save();
    }
}
