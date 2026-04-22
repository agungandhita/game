<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\User\UserServiceInterface;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    public function __construct(private UserServiceInterface $userService)
    {
    }

    public function index(Request $request)
    {
        $users = $this->userService->getPaginated($request->search);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nickname' => 'required|unique:users',
            'grade' => 'required|integer',
            'role' => 'required|in:admin,student',
        ]);

        $this->userService->create($validated);

        Alert::success('Berhasil', 'User berhasil ditambahkan!');

        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nickname' => 'required|unique:users,nickname,'.$user->id,
            'grade' => 'required|integer',
            'role' => 'required|in:admin,student',
        ]);

        $this->userService->update($user, $validated);

        Alert::success('Berhasil', 'User berhasil diperbarui!');

        return redirect()->route('admin.users.index');
    }

    public function destroy(User $user)
    {
        $this->userService->delete($user);
        Alert::success('Berhasil', 'User berhasil dihapus!');

        return redirect()->route('admin.users.index');
    }
}
