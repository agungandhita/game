<?php

namespace App\Services\User;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\User;

interface UserServiceInterface
{
    public function getPaginated(string $search = null): LengthAwarePaginator;
    public function create(array $data): User;
    public function update(User $user, array $data): User;
    public function delete(User $user): bool;
}
