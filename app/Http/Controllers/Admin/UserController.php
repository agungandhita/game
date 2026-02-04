<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $query->where('nickname', 'like', "%{$request->search}%");
        }

        $users = $query->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nickname' => 'required|unique:users',
            'grade' => 'required|integer',
            'role' => 'required|in:admin,student',
        ]);

        User::create([
            'nickname' => $request->nickname,
            'grade' => $request->grade,
            'role' => $request->role,
            'total_points' => 0,
        ]);

        Alert::success('Berhasil', 'User berhasil ditambahkan!');

        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'nickname' => 'required|unique:users,nickname,'.$user->id,
            'grade' => 'required|integer',
            'role' => 'required|in:admin,student',
        ]);

        $user->update([
            'nickname' => $request->nickname,
            'grade' => $request->grade,
            'role' => $request->role,
        ]);

        Alert::success('Berhasil', 'User berhasil diperbarui!');

        return redirect()->route('admin.users.index');
    }

    public function destroy(User $user)
    {
        $user->delete();
        Alert::success('Berhasil', 'User berhasil dihapus!');

        return redirect()->route('admin.users.index');
    }
}
