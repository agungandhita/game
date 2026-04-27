<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Quiz\Grade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ── Tampilkan form login ──────────────────────────────────────────────
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectAfterLogin();
        }
        return view('auth.login');
    }

    // ── Proses login ──────────────────────────────────────────────────────
    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return $this->redirectAfterLogin();
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Email atau password salah. Coba lagi ya!']);
    }

    // ── Tampilkan form register ───────────────────────────────────────────
    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectAfterLogin();
        }

        $grades = Grade::orderBy('order')->get();
        return view('auth.register', compact('grades'));
    }

    // ── Proses register ───────────────────────────────────────────────────
    public function register(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required'      => 'Nama wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email sudah terdaftar. Coba email lain.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => UserRole::Student->value,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('quiz.index')
            ->with('success', 'Selamat datang, ' . $user->name . '! Yuk mulai kuis! 🎉');
    }

    // ── Logout ────────────────────────────────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Berhasil keluar. Sampai jumpa! 👋');
    }

    // ── Helper redirect berdasarkan role ──────────────────────────────────
    private function redirectAfterLogin()
    {
        $user = Auth::user();

        if ($user->role === UserRole::Admin) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('quiz.index');
    }
}
