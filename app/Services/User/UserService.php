<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserService implements UserServiceInterface
{
    public function getPaginated(string $search = null): LengthAwarePaginator
    {
        $query = User::query();

        if ($search) {
            if (method_exists($query, 'whereLike')) {
                $query->whereLike('nickname', $search);
            } else {
                $query->where('nickname', 'like', "%{$search}%");
            }
        }

        return $query->paginate(10);
    }

    public function create(array $data): User
    {
        return User::create([
            'nickname' => $data['nickname'],
            'grade' => $data['grade'],
            'role' => $data['role'],
            'total_points' => 0,
        ]);
    }

    public function update(User $user, array $data): User
    {
        $user->update([
            'nickname' => $data['nickname'],
            'grade' => $data['grade'],
            'role' => $data['role'],
        ]);

        return $user;
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }
}
